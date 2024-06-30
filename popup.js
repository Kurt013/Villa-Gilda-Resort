// popup.js

// Get the confirmation dialog element
const confirmDeleteDialog = document.getElementById('confirmDeleteDialog');

// Function to open the delete confirmation dialog
function openDeleteConfirmation(deleteID, username) {
  const deleteConfirmationText = document.getElementById('deleteConfirmationText');
  deleteConfirmationText.textContent = `Are you sure you want to delete the staff member with username '${username}'?`;

  const deleteForm = document.getElementById('deleteForm');
  deleteForm.setAttribute('action', `?deleteID=${deleteID}`);

  confirmDeleteDialog.showModal();
}

// Event listener for closing the delete confirmation dialog
confirmDeleteDialog.querySelector('#exitDialog').addEventListener('click', function() {
  confirmDeleteDialog.close();
});

// Event listener for cancel button in delete confirmation dialog
confirmDeleteDialog.querySelector('#cancelDelete').addEventListener('click', function() {
  confirmDeleteDialog.close();
});
