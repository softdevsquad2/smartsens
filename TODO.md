# TODO: Fix Toolman Barang Edit and Delete Features

## Issues Identified:
1. **Delete Route Mismatch**: Route defined as `POST /toolman/toolman/barang/{id}` but view uses `DELETE /toolman/barang/{id}`
2. **Inconsistent Image Storage**: `storeBarang` uses 'uploads' folder, `update` uses 'barangs' folder
3. **Route Method**: Delete route should be DELETE, not POST

## Tasks:
- [ ] Fix delete route in routes/web.php: Change path and method
- [ ] Standardize image storage folder in ToolmanController
- [ ] Test edit functionality
- [ ] Test delete functionality
- [ ] Verify all CRUD operations work correctly
