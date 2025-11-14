function toggleClearIcon(input) {
  const icon = input.parentElement.querySelector(".cancel-icon");
  if (input.value.length > 0) {
    icon.style.display = "block";
  } else {
    icon.style.display = "none";
  }
}

function clearSearch(icon) {
  const form = icon.closest("form");
  if (form) {
    const input = form.querySelector('input[name="q"]');
    if (input) {
      input.value = "";
    }
    form.submit();
  }
}

const modal = document.getElementById("delete-modal");
const confirmButton = document.getElementById("modal-confirm-delete");
const modalText = document.getElementById("modal-delete-text");

function confirmDelete(event, deleteUrl, name) {
  event.preventDefault();
  modalText.textContent = `Tem certeza que deseja excluir o registro do bairro: "${name}"? Esta ação não pode ser desfeita.`;
  confirmButton.href = deleteUrl;
  modal.style.display = "flex";
}
function closeDeleteModal() {
  modal.style.display = "none";
  confirmButton.href = "#";
}

window.onclick = function (event) {
  if (event.target == modal) {
    closeDeleteModal();
  }
};

function validateForm(event) {
  const form = event.target;
  const inputs = form.querySelectorAll("input[name]");
  const errorMessageDiv = document.getElementById("js-error-message");

  let errors = [];

  for (const input of inputs) {
    if (input.value.trim() === "") {
      const label = form.querySelector(`label[for="${input.id}"]`);
      const labelText = label ? label.textContent.replace(":", "") : input.name;
      errors.push(`O campo '${labelText}' é obrigatório.`);
    }
  }

  if (errors.length > 0) {
    event.preventDefault();

    errorMessageDiv.innerHTML =
      "<strong>Por favor, corrija os erros:</strong><br>" + errors.join("<br>");
    errorMessageDiv.style.display = "block";

    inputs[0].focus();

    return false;
  }
  errorMessageDiv.style.display = "none";
  return true;
}
