//PARA USUARIO

// Validar senhas iguais
document.querySelector("form").addEventListener("submit", function (e) {
    const senha = document.getElementById("senha").value;
    const confirmar = document.getElementById("confirmarSenha").value;

    if (senha !== confirmar) {
        e.preventDefault();
        alert("As senhas não estão iguais!");
    }
});

// CPF máscara
const cpfInput = document.getElementById('cpf');
if (cpfInput) {
    cpfInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = value;
    });
}

// CNPJ máscara
const cnpjInput = document.getElementById('cnpj');
if (cnpjInput) {
    cnpjInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/^(\d{2})(\d)/, '$1.$2');
        value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
        value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
        e.target.value = value;
    });
}

// Telefone máscara
const telefoneInput = document.getElementById('telefone');
if (telefoneInput) {
    telefoneInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
        value = value.replace(/(\d{5})(\d{4})$/, '$1-$2');
        e.target.value = value;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Seleção dos elementos
    const pfCheckbox = document.getElementById('pf');
    const pjCheckbox = document.getElementById('pj');
    const voluntarioCheckbox = document.getElementById('voluntario');
    const adminCheckbox = document.getElementById('admin');

    const cpfField = document.getElementById('cpf-field');
    const cnpjField = document.getElementById('cnpj-field');

    // Máscaras para CPF e CNPJ (opcional)
    const cpfInput = document.getElementById('cpf');
    const cnpjInput = document.getElementById('cnpj');

    // Função para atualizar os campos
    function atualizarCampos() {
        // Garante que PF e PJ não fiquem marcados juntos
        if (pfCheckbox.checked && pjCheckbox.checked) {
            if (event.target === pfCheckbox) {
                pjCheckbox.checked = false;
            } else {
                pfCheckbox.checked = false;
            }
        }

        // Mostrar CPF se PF, Voluntário ou Admin estiverem marcados
        const mostrarCPF = pfCheckbox.checked || voluntarioCheckbox.checked || adminCheckbox.checked;

        // Mostrar CNPJ apenas se PJ estiver marcado
        const mostrarCNPJ = pjCheckbox.checked;

        // Aplica as classes para mostrar/esconder os campos
        cpfField.classList.toggle('d-none', !mostrarCPF);
        cnpjField.classList.toggle('d-none', !mostrarCNPJ);

        // Adiciona/remove o atributo required conforme necessário
        cpfInput.required = mostrarCPF;
        cnpjInput.required = mostrarCNPJ;
    }

    // Adiciona os event listeners
    [pfCheckbox, pjCheckbox, voluntarioCheckbox, adminCheckbox].forEach(checkbox => {
        checkbox.addEventListener('change', atualizarCampos);
    });

    // Máscaras para CPF e CNPJ (opcional)
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });
    }

    if (cnpjInput) {
        cnpjInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }

    // Executa uma vez no carregamento para configurar o estado inicial
    atualizarCampos();
});
//PARA ENDERECO

// confirmacao.js
function aplicarMascaraCEP() {
    const cepInput = document.getElementById('cep');

    if (cepInput) {
        // Máscara do CEP (formato 00000-000)
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value.substring(0, 9);
        });

        // Busca automática ao sair do campo
        cepInput.addEventListener('blur', function(e) {
            const cep = e.target.value.replace(/\D/g, '');

            if (cep.length === 8) {
                buscarEndereco(cep);
            } else if (cep.length > 0) {
                alert('CEP incompleto! Digite 8 dígitos.');
                cepInput.focus();
            }
        });
    }
}

function buscarEndereco(cep) {
    const cepInput = document.getElementById('cep');
    cepInput.classList.add('loading-cep');

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => {
            if (!response.ok) throw new Error("Erro na conexão");
            return response.json();
        })
        .then(data => {
            if (data.erro) {
                throw new Error("CEP não encontrado");
            }

            // Preenche os campos
            document.getElementById('rua').value = data.logradouro || '';
            document.getElementById('bairro').value = data.bairro || '';
            document.getElementById('cidade').value = data.localidade || '';
            document.getElementById('estado').value = data.uf || '';

            // Foca no número
            document.getElementById('numero').focus();
        })
        .catch(error => {
            console.error("Erro na busca:", error);
            alert(error.message);
        })
        .finally(() => {
            cepInput.classList.remove('loading-cep');
        });
}

// Inicia quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    aplicarMascaraCEP();

});