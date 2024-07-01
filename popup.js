function openDialog(button) {
  const dialog = document.querySelector('.confirm-popup');
  const deleteForm = document.getElementById('confirmDeleteForm');
  const deleteID = button.closest('form').querySelector('input[name="deleteID"]').value;

  deleteForm.querySelector('input[name="deleteID"]').value = deleteID;
  dialog.showModal();
}

function closeDialog() {
  const dialog = document.querySelector('dialog');
  dialog.close();
}

const messageDialog = document.querySelector('.message-popup');
messageDialog.showModal();