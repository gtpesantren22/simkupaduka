  /* Default Notifications */

const flashData = $('.flash-data').data('flashdata');
const flashDataError = $('.flash-data-error').data('flashdata');

if (flashData) {
	Lobibox.notify('success', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		icon: 'bx bx-check-circle',
		msg: flashData
	});
}
if (flashDataError) {
	Lobibox.notify('error', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		icon: 'bx bx-x-circle',
		msg: flashDataError
	});
}

$('.tombol-hapus').on('click', function (e) {
	
	e.preventDefault();
	const hreh = $(this).attr('href');

	Swal.fire({
		title: 'Yakin ?',
		text: 'Data ini akan dihapus permanent',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Hapus Data!',
		
	}).then((result) => {
		if (result.value) {
			document.location.href = hreh
		}
	});
	
});

$('.tbl-confirm').on('click', function (e) {
	
	e.preventDefault();
	const hreh = $(this).attr('href');
	const isi = $(this).attr('value');

	Swal.fire({
		title: 'Yakin ?',
		text: isi,
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Lanjutkan!',
		
	}).then((result) => {
		if (result.value) {
			document.location.href = hreh
		}
	});
	
});