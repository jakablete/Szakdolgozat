document.addEventListener("DOMContentLoaded", function () {
  const deleteProfileLink = document.getElementById("deleteProfileLink");
  const confirmationDialog = document.getElementById("confirmationDialog");
  const confirmDeleteButton = document.getElementById("confirmDelete");
  const cancelDeleteButton = document.getElementById("cancelDelete");

  deleteProfileLink.addEventListener("click", function () {
    confirmationDialog.style.display = "block";
  });

  confirmDeleteButton.addEventListener("click", function () {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "profile-delete.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        if (xhr.responseText === "success") {
          alert("Profile deleted successfully");
          window.location.href = "admin.php";
        } else {
          alert("Profile deletion failed");
        }
        confirmationDialog.style.display = "none";
      }
    };
    xhr.send();
  });

  cancelDeleteButton.addEventListener("click", function () {
    confirmationDialog.style.display = "none";
  });
});


