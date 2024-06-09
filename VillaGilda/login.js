  const dialog = document.querySelector("dialog");
  const showButton = document.querySelector(".submit-btn");
  const closeButton = document.querySelector("dialog button");


  
  // "Close" button closes the dialog
  closeButton.addEventListener("click", () => {
    dialog.close();
  });