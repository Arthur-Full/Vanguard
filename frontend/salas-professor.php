<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas | Vanguard</title>
    <link rel="stylesheet" href="css/salas-professor.css">
</head>

<body>
    <div class="page-shell">
        <aside class="sidebar">
            <div class="brand-wrap">
                <img src="imagem/logo-maior.png" alt="Vanguard" class="brand-logo">
            </div>

            <div class="user-box">
                <div class="user-avatar">
                    <img src="imagem/perfil.png" alt="Alce Barbosa">
                </div>
                <div class="user-name-wrap">
                    <strong>Alce Barbosa</strong>
                    <span>Professor</span>
                </div>
            </div>

            <nav class="side-nav" aria-label="Navegação principal">
                <a href="dash-professor.php" class="nav-item">
                    <img src="imagem/casa-branca.png" alt="" aria-hidden="true">
                    <span>DashBoard</span>
                </a>

                <div class="nav-dropdown aberto">
                    <a href="#" class="nav-item nav-toggle" aria-expanded="true" aria-controls="submenu-salas">
                        <img src="imagem/salas-branco.png" alt="" aria-hidden="true">
                        <span>Salas</span>
                        <i class="seta" aria-hidden="true"></i>
                    </a>
                    <div class="submenu" id="submenu-salas">
                        <ul>
                            <li><a href="#" data-sala="1MD">1MD</a></li>
                            <li><a href="#" data-sala="2MD">2MD</a></li>
                            <li><a href="#" data-sala="3MD" class="sel">3MD</a></li>
                        </ul>
                    </div>
                </div>

                <a href="calendario-professor.php" class="nav-item">
                    <img src="imagem/calendario-01.png" alt="" aria-hidden="true">
                    <span>Calendário / aulas do dia</span>
                </a>
                <a href="notas-professor.php" class="nav-item">
                    <img src="imagem/atividades-branco.png" alt="" aria-hidden="true">
                    <span>Atividades</span>
                </a>
                <a href="notas-professor.php" class="nav-item">
                    <img src="imagem/notas-branco.png" alt="" aria-hidden="true">
                    <span>Notas</span>
                </a>
            </nav>

            <a href="#" class="btn-sair">Sair</a>
        </aside>

        <div class="main-column">
            <main class="content-area sala-area">
                <div class="sala-piso">
                    <div class="sala-prof" title="Mesa do professor">
                        <span class="sala-prof-cadeira"></span>
                        <div class="sala-prof-mesa"></div>
                    </div>

                    <div class="sala-carteiras">
                        <div class="sala-bloco" id="bloco-esq"></div>
                        <div class="sala-bloco" id="bloco-dir"></div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Todas as carteiras começam ativas (verde). 8 fileiras x 6 carteiras (3 à esquerda + 3 à direita)
        const MAPA = Array(8).fill("GGGGGG");

        const SALAS = { "1MD": MAPA, "2MD": MAPA, "3MD": MAPA };

        const blocoEsq = document.getElementById("bloco-esq");
        const blocoDir = document.getElementById("bloco-dir");

        // Nomes dos alunos (um por carteira)
        const NOMES = [
            "Ana", "Bruno", "Carla", "Diego", "Eduarda", "Felipe", "Gabriela", "Heitor",
            "Isabela", "João", "Karen", "Lucas", "Marina", "Nicolas", "Olívia", "Pedro",
            "Rafaela", "Samuel", "Talita", "Vitor", "Yasmin", "Arthur", "Beatriz", "Caio",
            "Daniela", "Enzo", "Fernanda", "Gustavo", "Helena", "Igor", "Júlia", "Kauê",
            "Larissa", "Miguel", "Natália", "Otávio", "Paula", "Renan", "Sofia", "Thiago",
            "Valentina", "Wesley", "Alice", "Davi", "Elisa", "Fábio", "Giovana", "Henrique"
        ];
        const DESLOCAMENTO = { "1MD": 0, "2MD": 16, "3MD": 32 };

        // Carteiras desativadas por sala (guardado no navegador, sobrevive à troca de sala e ao recarregar)
        const CHAVE = "vanguard-carteiras-desativadas";
        let desativadas = {};
        try { desativadas = JSON.parse(localStorage.getItem(CHAVE)) || {}; } catch (e) { desativadas = {}; }

        function salvarDesativadas() {
            try { localStorage.setItem(CHAVE, JSON.stringify(desativadas)); } catch (e) { /* sem armazenamento */ }
        }

        function criarCarteira(sala, numero, estado, ordem, nome) {
            const b = document.createElement("button");
            b.type = "button";
            b.className = "carteira " + (estado === "G" ? "ok" : "falta");
            b.style.setProperty("--i", ordem);
            b.title = "Carteira " + numero + " - " + nome;
            b.setAttribute("aria-pressed", "false");
            b.setAttribute("aria-label", nome + ", carteira " + numero + " - ativa");
            b.innerHTML = '<span class="nome"></span><span class="tampo"></span><span class="status"></span>';
            b.querySelector(".nome").textContent = nome;

            const lista = desativadas[sala] || [];
            if (lista.includes(numero)) {
                b.classList.add("desativada");
                b.setAttribute("aria-pressed", "true");
                b.setAttribute("aria-label", nome + ", carteira " + numero + " - desativada");
            }

            b.addEventListener("click", () => {
                const off = b.classList.toggle("desativada");
                b.setAttribute("aria-pressed", off);
                b.setAttribute("aria-label", nome + ", carteira " + numero + (off ? " - desativada" : " - ativa"));

                const atual = (desativadas[sala] || []).filter((n) => n !== numero);
                if (off) atual.push(numero);
                desativadas[sala] = atual;
                salvarDesativadas();
                const st = b.querySelector(".status");
                st.classList.remove("troca");
                void st.offsetWidth;
                st.classList.add("troca");
            });
            return b;
        }

        function renderSala(nome_sala) {
            blocoEsq.innerHTML = "";
            blocoDir.innerHTML = "";
            SALAS[nome_sala].forEach((linha, r) => {
                [...linha].forEach((estado, c) => {
                    const numero = r * 6 + c + 1;
                    const ordem = r * 3 + (c % 3);
                    const nome = NOMES[(numero - 1 + DESLOCAMENTO[nome_sala]) % NOMES.length];
                    (c < 3 ? blocoEsq : blocoDir).appendChild(criarCarteira(nome_sala, numero, estado, ordem, nome));
                });
            });
        }

        // Dropdown "Salas"
        const dropdown = document.querySelector(".nav-dropdown");
        const toggle = dropdown.querySelector(".nav-toggle");

        toggle.addEventListener("click", (e) => {
            e.preventDefault();
            const aberto = dropdown.classList.toggle("aberto");
            toggle.setAttribute("aria-expanded", aberto);
        });

        dropdown.querySelectorAll(".submenu a").forEach((link) => {
            link.addEventListener("click", (e) => {
                e.preventDefault();
                dropdown.querySelectorAll(".submenu a").forEach((l) => l.classList.remove("sel"));
                link.classList.add("sel");
                renderSala(link.dataset.sala);
            });
        });

        const salaSolicitada = new URLSearchParams(window.location.search).get("sala");
        const salaInicial = Object.prototype.hasOwnProperty.call(SALAS, salaSolicitada) ? salaSolicitada : "3MD";
        dropdown.querySelectorAll(".submenu a").forEach((link) => {
            link.classList.toggle("sel", link.dataset.sala === salaInicial);
        });
        renderSala(salaInicial);
    </script>
</body>

</html>
