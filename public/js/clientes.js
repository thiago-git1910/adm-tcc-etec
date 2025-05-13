document.addEventListener('DOMContentLoaded', function() {


document.getElementById("g").addEventListener("click", function() {
    var tabelaClientes = document.querySelector(".tabelaClientes");
    tabelaClientes.classList.remove("vermelho"); // Remove a classe vermelho se estiver presente
    tabelaClientes.classList.remove("branco"); // Remove a classe branca se estiver presente
    tabelaClientes.classList.add("verde"); // Adiciona a classe verde
});

document.getElementById("r").addEventListener("click", function() {
    var tabelaClientes = document.querySelector(".tabelaClientes");
    tabelaClientes.classList.remove("verde"); // Remove a classe verde se estiver presente
    tabelaClientes.classList.remove("branco"); // Remove a classe branco se estiver presente
    tabelaClientes.classList.add("vermelho"); // Adiciona a classe vermelho
});
document.getElementById("w").addEventListener("click", function() {
    var tabelaClientes = document.querySelector(".tabelaClientes");
    tabelaClientes.classList.remove("verde"); // Remove a classe verde se estiver presente
    tabelaClientes.classList.remove("vermelho"); // Remove a classe vermeho se estiver presente
    tabelaClientes.classList.add("branco"); // Adiciona a classe vermelho
});
});
