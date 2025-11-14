// Funções da página principal (index.php)

// Lógica do "X" de limpar busca
function toggleClearIcon(input) {
  const icon = input.parentElement.querySelector(".cancel-icon");
  if (icon) {
    // Verifica se o ícone existe
    if (input.value.length > 0) {
      icon.style.display = "block";
    } else {
      icon.style.display = "none";
    }
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

// 2. Lógica do popup de exclusão (painel de popup)

// Tenta encontrar os elementos do popup (IDs CORRIGIDOS)
const popup = document.getElementById("delete-popup");
const confirmButton = document.getElementById("popup-confirm-delete");
const popupText = document.getElementById("popup-delete-text");

// SÓ executa o código do popup SE o popup existir nesta página
if (popup && confirmButton && popupText) {
  function confirmDelete(event, deleteUrl, name) {
    event.preventDefault();
    popupText.textContent = `Tem certeza que deseja excluir o registro do bairro: "${name}"? Esta ação não pode ser desfeita.`;
    confirmButton.href = deleteUrl;
    popup.style.display = "flex";
  }

  function closeDeletepopup() {
    popup.style.display = "none";
    confirmButton.href = "#";
  }

  // Fecha o popup se clicar no fundo
  window.onclick = function (event) {
    if (event.target == popup) {
      closeDeletepopup();
    }
  };
}

// Funções das páginas de formulário (insert.php / update.php)

// Lógica de validação de formulário

// SÓ define a função 'validateForm' SE ela for necessária (nas páginas de form)
const formDeCadastro = document.querySelector(".formulario-cadastro");
if (formDeCadastro) {
  function validateForm(event) {
    const form = event.target;
    // Tenta encontrar a div de erro
    const errorMessageDiv = document.getElementById("js-error-message");

    // SÓ executa a validação SE a div de erro existir nesta página
    if (errorMessageDiv) {
      const inputs = form.querySelectorAll("input[name]");
      let errors = [];

      for (const input of inputs) {
        // Verifica se o campo está visível antes de validar (boa prática)
        if (input.offsetParent !== null && input.value.trim() === "") {
          const label = form.querySelector(`label[for="${input.id}"]`);
          const labelText = label
            ? label.textContent.replace(":", "")
            : input.name;
          errors.push(`O campo '${labelText}' é obrigatório.`);
        }
      }

      if (errors.length > 0) {
        event.preventDefault(); // Impede o envio do formulário

        errorMessageDiv.innerHTML =
          "<strong>Por favor, corrija os erros:</strong><br>" +
          errors.join("<br>");
        errorMessageDiv.style.display = "block";

        if (inputs[0]) {
          inputs[0].focus(); // Foca no primeiro campo
        }
        return false; // Falha na validação
      }

      errorMessageDiv.style.display = "none"; // Esconde erros (se tudo estiver OK)
      return true; // Sucesso na validação
    }

    // Se a div de erro não existe, não faz nada e permite o envio
    return true;
  }
}
