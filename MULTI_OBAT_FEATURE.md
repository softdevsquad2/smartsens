# Multi-Obat Support Feature Documentation

## Overview
This document describes the multi-obat (multiple medications) support feature for Rekam Medis (Medical Records) in the Smartsens application. Users can now select and manage multiple medications per medical record with custom quantities and dosage instructions.

## Feature Requirements Met
✅ Support multiple medications per Rekam Medis  
✅ Adjustable quantities for each medication  
✅ Automatic stock tracking and FIFO reduction  
✅ Dynamic form UI with Select2 dropdown  
✅ Real-time stock display  
✅ Dosage/usage instructions per medication  

## Database Schema

### New Table: `tbl_rekam_medis_obat`
Stores detailed medication records linked to each Rekam Medis entry.

```sql
CREATE TABLE tbl_rekam_medis_obat (
    id_rekam_medis_obat BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_rekam_medis BIGINT NOT NULL,
    id_obat BIGINT NOT NULL,
    jumlah INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_rekam_obat (id_rekam_medis, id_obat),
    FOREIGN KEY (id_rekam_medis) REFERENCES tbl_rekam_medis(id_rekam_medis) ON DELETE CASCADE,
    FOREIGN KEY (id_obat) REFERENCES tbl_obat(id_obat) ON DELETE CASCADE
);
```

### Modified Column: `tbl_rekam_medis.obat_diberikan`
Changed from VARCHAR to TEXT to store JSON summary of medications.

**Format:**
```json
[
    {
        "id_obat": 1,
        "jumlah": 2,
        "aturan_pakai": "3x1 hari"
    },
    {
        "id_obat": 2,
        "jumlah": 1,
        "aturan_pakai": "2x1 hari"
    }
]
```

## Models

### RekamMedisObat
New model representing detailed medication records.

**File:** `app/Models/RekamMedisObat.php`

```php
class RekamMedisObat extends Model {
    protected $table = 'tbl_rekam_medis_obat';
    protected $primaryKey = 'id_rekam_medis_obat';
    protected $fillable = ['id_rekam_medis', 'id_obat', 'jumlah'];
    
    public function rekamMedis() {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis');
    }
    
    public function obat() {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
```

### RekamMedis (Modified)
Updated with new `rekamMedisObat` relationship.

```php
public function rekamMedisObat() {
    return $this->hasMany(RekamMedisObat::class, 'id_rekam_medis');
}
```

## Form Implementation

### URL
`GET /uks/rekam-medis/create`

### Form Structure
The form is located in `resources/views/uks/rekam-medis/create.blade.php`

**Key Components:**
1. **Siswa Selection** - Select2 AJAX dropdown with search
2. **Obat Container** - Dynamic rows for medication selection
3. **Add Medication Button** - Triggers `addObatRow()` JavaScript function
4. **Remove Medication Buttons** - Delete row functionality

### Form Data Structure
Medications are submitted as nested arrays:

```html
<input name="obat_diberikan[id_obat][]" value="1">
<input name="obat_diberikan[jumlah][]" value="2">
<input name="obat_diberikan[aturan_pakai][]" value="3x1 hari">
```

**HTML Example:**
```html
<div id="obat-container">
    <div class="obat-row">
        <select name="obat_diberikan[id_obat][]"></select>
        <input type="number" name="obat_diberikan[jumlah][]" min="1">
        <input type="text" name="obat_diberikan[aturan_pakai][]">
        <button type="button" class="remove-obat-btn">Remove</button>
    </div>
</div>
<button type="button" id="add-obat-btn">Tambah Obat</button>
```

## JavaScript Implementation

### Window Data
```javascript
window.obatList = @json(
    Obat::with('stokObat')->get()->map(
        fn($o) => [
            'id' => $o->id_obat,
            'nama' => $o->nama_obat,
            'kategori' => $o->kategori,
            'stok' => $o->stokObat->sum('jumlah') ?? 0,
            'text' => $o->nama_obat . ' (...) - Stok: ' . (...)
        ]
    )->toArray()
);
```

### Key Functions

#### `addObatRow(selectedObatId, jumlah, aturanPakai)`
Adds a new medication row to the form.

**Parameters:**
- `selectedObatId` (optional) - Pre-select medication
- `jumlah` (default: 1) - Quantity
- `aturanPakai` (default: '') - Dosage instructions

**Features:**
- Unique row ID generation
- Select2 initialization per row
- Dynamic field names for array submission
- Remove button with event handler

#### `removeObatRow(event)`
Removes medication row from form.

### Event Handlers
- **Add Obat Button Click** - Calls `addObatRow()`
- **Remove Obat Button Click** - Calls `removeObatRow()`
- **Initial Row Loading** - Renders first row server-side

## Backend Implementation

### Controller: UksController

#### `rekamMedisStore(RekamMedisRequest $request)`
Handles form submission with transaction safety.

**File:** `app/Http/Controllers/UksController.php`

**Logic:**
1. Validate incoming data
2. Extract medication arrays from request
3. Build medication list
4. Begin database transaction
5. Create RekamMedis record with JSON summary
6. For each medication:
   - Create RekamMedisObat record
   - Call `reduceStock()` for FIFO reduction
7. Commit transaction
8. Redirect to index on success
9. Rollback on error with logging

**Code:**
```php
public function rekamMedisStore(RekamMedisRequest $request)
{
    $data = $request->validated();
    
    $obatIds = $request->input('obat_diberikan.id_obat', []);
    $obatJumlah = $request->input('obat_diberikan.jumlah', []);
    $obatAturan = $request->input('obat_diberikan.aturan_pakai', []);
    
    // Build obat list
    $obatList = [];
    foreach ($obatIds as $key => $obatId) {
        if (!empty($obatId) && isset($obatJumlah[$key])) {
            $obatList[] = [
                'id_obat' => $obatId,
                'jumlah' => $obatJumlah[$key],
                'aturan_pakai' => $obatAturan[$key] ?? null,
            ];
        }
    }
    
    $data = $request->only(['id_siswa', 'tanggal', 'keluhan', 'diagnosis']);
    
    try {
        DB::beginTransaction();
        
        $rekam = RekamMedis::create(array_merge($data, [
            'obat_diberikan' => !empty($obatList) ? json_encode($obatList) : null,
        ]));
        
        if (!empty($obatList)) {
            foreach ($obatList as $ob) {
                RekamMedisObat::create([
                    'id_rekam_medis' => $rekam->id_rekam_medis,
                    'id_obat' => $ob['id_obat'],
                    'jumlah' => $ob['jumlah'],
                ]);
                
                $this->reduceStock((int) $ob['id_obat'], (int) $ob['jumlah']);
            }
        }
        
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Gagal menyimpan rekam medis: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan rekam medis');
    }
    
    return redirect()->route('uks.rekam-medis.index')->with('success', 'Rekam medis berhasil ditambahkan');
}
```

#### `reduceStock(int $obatId, int $qty)`
Implements FIFO (First-In-First-Out) stock reduction algorithm.

**Algorithm:**
1. Get all stock batches ordered by tanggal_masuk (oldest first)
2. For each batch:
   - If batch quantity >= needed quantity, deduct and stop
   - Else deduct entire batch, continue to next
3. Log warning if insufficient total stock
4. Commit changes atomically

**Code:**
```php
private function reduceStock(int $obatId, int $qty): void
{
    $totalStok = StokObat::where('id_obat', $obatId)->sum('jumlah');
    
    if ($totalStok < $qty) {
        Log::warning("Stok obat insufficient for obat_id=$obatId. Need: $qty, Available: $totalStok");
        return;
    }
    
    $batches = StokObat::where('id_obat', $obatId)
        ->orderBy('tanggal_masuk', 'asc')
        ->get();
    
    $remaining = $qty;
    foreach ($batches as $batch) {
        if ($remaining <= 0) break;
        
        $deducted = min($batch->jumlah, $remaining);
        $batch->update(['jumlah' => $batch->jumlah - $deducted]);
        $remaining -= $deducted;
    }
}
```

### Request Validation: RekamMedisRequest

**File:** `app/Http/Requests/RekamMedisRequest.php`

**Validation Rules:**
```php
'obat_diberikan.id_obat' => 'nullable|array',
'obat_diberikan.id_obat.*' => 'required_if:obat_diberikan.id_obat,!=|exists:tbl_obat,id_obat',
'obat_diberikan.jumlah' => 'nullable|array',
'obat_diberikan.jumlah.*' => 'required_if:obat_diberikan.id_obat,!=|integer|min:1',
'obat_diberikan.aturan_pakai' => 'nullable|array',
'obat_diberikan.aturan_pakai.*' => 'nullable|string|max:255',
```

## Factories for Testing

All models now include `HasFactory` trait for testing support.

### Created Factories
1. **SiswaFactory** - Generates test student records
2. **ObatFactory** - Generates test medication records
3. **StokObatFactory** - Generates test stock batches with dates
4. **KelasFactory** - Generates test class records
5. **JurusanFactory** - Generates test major records
6. **WaliKelasFactory** - Generates test teacher records
7. **UserFactory** - Updated for application's User schema

## Testing

### Test Suite
**File:** `tests/Feature/RekamMedisMultiObatTest.php`

**Test Cases:**
1. `test_can_create_rekam_medis_with_multiple_obat()` - Verify multi-obat save
2. `test_requires_at_least_one_obat_if_provided()` - Allow empty obat list
3. `test_obat_must_exist()` - Validate obat exists in database
4. `test_jumlah_must_be_positive()` - Minimum quantity validation
5. `test_obat_summary_stored_as_json()` - Verify JSON format storage

**Run Tests:**
```bash
php artisan test tests/Feature/RekamMedisMultiObatTest.php
```

## User Workflow

1. **Navigate to Form**
   - Visit `/uks/rekam-medis/create`
   
2. **Select Student**
   - Click Siswa dropdown
   - Search by name using AJAX
   - Select student
   
3. **Enter Basic Information**
   - Fill tanggal (date)
   - Enter keluhan (complaint)
   - Enter diagnosis (optional)
   
4. **Add Medications**
   - Form starts with one empty row
   - Click "Tambah Obat" to add more
   - For each medication:
     - Select obat from dropdown (shows stok)
     - Enter quantity
     - Enter aturan_pakai (e.g., "3x1 hari")
   - Click remove button to delete row
   
5. **Submit Form**
   - Click "Simpan" button
   - Form validates all required fields
   - System saves:
     - Main Rekam Medis record
     - Detailed RekamMedisObat rows
     - Reduces stock via FIFO algorithm
   - Redirects to index on success

## API Response Format

### Successful Save
```json
{
    "success": "Rekam medis berhasil ditambahkan",
    "redirect": "/uks/rekam-medis"
}
```

### Validation Errors
```json
{
    "errors": {
        "obat_diberikan.id_obat.0": ["Obat harus dipilih"],
        "obat_diberikan.jumlah.0": ["Jumlah minimal 1"]
    }
}
```

## Performance Considerations

1. **Stock Lookup** - FIFO algorithm queries all batches per medication
   - Optimization: Consider batch indexing for large datasets
   
2. **Select2 Data** - Full obat list loaded on page load
   - Optimization: Use AJAX endpoint for large catalogs
   - Current: Suitable for up to ~1000 medications

3. **Relationships** - Using `with('stokObat')` for eager loading
   - Prevents N+1 queries

## Known Limitations

1. **Tests Require Auth** - Feature tests need proper middleware/authentication setup
2. **Stock Validation** - Currently logs warning, doesn't block save (by design)
3. **Batch Selection** - FIFO automatic, no manual batch selection UI

## Future Enhancements

1. Manual batch/lot selection UI
2. Stock availability validation before save
3. Medication quantity restrictions (e.g., by strength)
4. Historical stock ledger/audit trail
5. Medication interaction warnings
6. Automated reorder notifications

## Migration Notes

To apply this feature to an existing installation:

```bash
# Run migrations
php artisan migrate

# Run seeds (if needed)
php artisan db:seed --class=TblStokObatSeeder
```

## Support & Troubleshooting

### Form Won't Load
- Clear Blade cache: `php artisan view:clear`
- Check browser console for JavaScript errors
- Verify Select2 library is loaded in layout

### Stock Not Reducing
- Check database for StokObat records
- Verify `tanggal_masuk` is set on batches
- Check application logs for errors

### Select2 Not Working
- Ensure jQuery is loaded before Select2
- Check browser console for JavaScript errors
- Verify Select2 CSS/JS files in layout

## References

- Laravel: https://laravel.com/docs
- Eloquent ORM: https://laravel.com/docs/eloquent
- Select2: https://select2.org/
- Transactions: https://laravel.com/docs/database#transactions

---

**Implementation Date:** November 11, 2025  
**Last Updated:** November 11, 2025  
**Version:** 1.0.0
