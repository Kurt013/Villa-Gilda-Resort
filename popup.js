/* For confirmation delete staff */
function openDialog(button) {
  const dialog = document.querySelector('.confirm-popup');
  const deleteForm = document.getElementById('confirmDeleteForm');
  const deleteID = button.closest('.delete').querySelector('input[name="deleteID"]').value;

  deleteForm.querySelector('input[name="deleteID"]').value = deleteID;
  dialog.showModal();
}

/* For confirmation delete reservation */
function openDialog2(button) {
  const dialog = document.querySelector('.confirm-popup');
  const deleteForm = document.getElementById('confirmDeleteReservation');
  const deleteID = button.closest('.delete').querySelector('input[name="id"]').value;

  deleteForm.querySelector('input[name="id"]').value = deleteID;
  dialog.showModal();
}

/* General exit button for all dialogs */
function closeDialog() {
  const dialog = document.querySelector('dialog');
  dialog.close();
}

const messageDialog = document.querySelector('.message-popup');
messageDialog.showModal();