//PARA USUARIO

// Validar senhas iguais
document.querySelector("form").addEventListener("submit", function (e) {
    const senha = document.getElementById("senha").value;
    const confirmar = document.getElementById("confirmarSenha").value;

    // if (senha !== confirmar) {
    //     e.preventDefault();
    //     alert("As senhas não estão iguais!");
    // }
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

//PARA ENDERECO


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