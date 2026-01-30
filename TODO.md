# TODO: Connect Attendance with Violation Feature

## Completed Tasks
- [x] Add imports for Pelanggaran and RekamPelanggaran models in AbsensiController.php
- [x] Implement logic to record late violation when student is late during absenMasuk
  - Find 'Terlambat Masuk Sekolah' violation from tbl_pelanggaran
  - Insert record into tbl_rekam_pelanggaran with:
    - foto_pelanggaran: null
    - id_user: null (empty)
    - pelapor: 'system'
    - Points from tbl_pelanggaran

## Requirements Met
- [x] When student is late, add violation point from tbl_pelanggaran
- [x] Insert into tbl_rekam_pelanggaran with null image and empty id_user
- [x] For late violations, reporter is 'system'
- [x] Points are from tbl_rekam_pelanggaran (via tbl_pelanggaran relationship)

## Testing Needed
- Test late attendance scenario to ensure violation is recorded
- Verify violation points are correctly retrieved from database
