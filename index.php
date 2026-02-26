<?php
// --- SEGURANÇA: Forçar HTTPS ---
$is_https = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

if (!$is_https) {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensagem_envio = "";
$erro_envio = false;

// CONFIGURAÇÕES DE E-MAIL
const SMTP_HOST = 'smtp.gmail.com';
const SMTP_USER = 'guilhermesantiago921@gmail.com';
const SMTP_PASS = 'jvga amcj bggh qeks';
const SMTP_PORT = 587;
const EMAIL_DESTINO = 'guilhermesantiago921@gmail.com';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $whatsapp = filter_input(INPUT_POST, 'whatsapp', FILTER_SANITIZE_SPECIAL_CHARS);
    $interesse = filter_input(INPUT_POST, 'interesse', FILTER_SANITIZE_SPECIAL_CHARS);

    if($nome && $email && $whatsapp) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom(SMTP_USER, 'Infraweb System');
            $mail->addAddress(EMAIL_DESTINO);
            $mail->addReplyTo($email, $nome);

            $mail->isHTML(true);
            $mail->Subject = "⚡ SYSTEM ALERT: $nome ($interesse)";
            $mail->Body = "<div style='background:#000; color:#0f0; font-family:monospace; padding:20px; border:1px solid #0f0;'><h2>>> NEW CONNECTION</h2><p>USER: $nome</p><p>CONTACT: $whatsapp</p><p>TARGET: $interesse</p></div>";
            $mail->AltBody = "Nome: $nome | Zap: $whatsapp";

            $mail->send();
            $mensagem_envio = "SISTEMA: DADOS ENVIADOS COM SUCESSO.";
        } catch (Exception $e) {
            $erro_envio = true;
            $mensagem_envio = "ERRO CRÍTICO: FALHA NA TRANSMISSÃO."; 
        }
    } else {
        $erro_envio = true;
        $mensagem_envio = "ERRO: INPUTS VAZIOS DETECTADOS.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFRAWEB // SYSTEM OVERRIDE</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Fontes Brutalistas e Monospaced -->
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@100;400;700&family=Syne:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        mono: ['JetBrains Mono', 'monospace'],
                        display: ['Syne', 'sans-serif'],
                    },
                    colors: {
                        void: '#050505',
                        acid: '#ccff00', // Verde Ácido/Neon
                        concrete: '#1a1a1a',
                    },
                    cursor: {
                        'none': 'none',
                    }
                }
            }
        }
    </script>

    <style>
        body { 
            background-color: #050505; 
            color: #ededed; 
            overflow-x: hidden;
        }

        /* Ocultar cursor custom em touch devices */
        @media (hover: none) {
            #cursor { display: none !important; }
            body { cursor: auto; }
        }

        /* Noise Texture Overlay */
        .noise {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none;
            z-index: 9998;
            opacity: 0.05;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* Custom Cursor */
        #cursor {
            width: 20px; height: 20px;
            background: #ccff00;
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            mix-blend-mode: difference; /* Inverte a cor do fundo */
            transition: transform 0.1s;
        }

        /* Typography */
        .outline-text {
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.3);
            color: transparent;
            transition: all 0.3s;
        }
        .outline-text:hover {
            color: #ccff00;
            -webkit-text-stroke: 0px;
        }

        /* Brutalist Grid */
        .b-border { border: 1px solid rgba(255, 255, 255, 0.1); }
        .b-border-t { border-top: 1px solid rgba(255, 255, 255, 0.1); }
        .b-border-b { border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .b-border-l { border-left: 1px solid rgba(255, 255, 255, 0.1); }

        /* Marquee Animation */
        .marquee {
            white-space: nowrap;
            overflow: hidden;
            box-sizing: border-box;
        }
        .marquee p {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 20s linear infinite;
        }
        @keyframes marquee { 0% { transform: translate(0, 0); } 100% { transform: translate(-100%, 0); } }

        /* Preloader */
        #preloader {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: #000; z-index: 10000;
            display: flex; justify-content: center; align-items: center;
            flex-direction: column;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Preloader -->
    <div id="preloader">
        <div class="font-mono text-acid text-sm md:text-xl">
            > INITIALIZING INFRAWEB KERNEL...<br>
            > LOADING ASSETS...<br>
            > SYSTEM READY.
        </div>
        <div class="w-64 h-1 bg-gray-800 mt-4 overflow-hidden">
            <div id="loader-bar" class="h-full bg-acid w-0"></div>
        </div>
    </div>

    <!-- Custom Cursor -->
    <div id="cursor" class="hidden md:block"></div>
    
    <!-- Noise Overlay -->
    <div class="noise"></div>

    <!-- Navbar -->
    <nav class="fixed w-full z-50 top-0 mix-blend-difference px-4 md:px-6 py-4 md:py-6 flex justify-between items-center">
        <a href="#" class="font-display font-bold text-xl md:text-2xl tracking-tighter text-white hover:text-acid transition-colors">
            INFRA<span class="text-acid">WEB</span>_
        </a>
        <div class="hidden md:flex gap-8 font-mono text-xs tracking-widest text-white">
            <a href="#manifesto" class="hover:underline decoration-acid underline-offset-4">01. MANIFESTO</a>
            <a href="#services" class="hover:underline decoration-acid underline-offset-4">02. PROTOCOLOS</a>
            <a href="#contact" class="hover:underline decoration-acid underline-offset-4">03. INICIAR</a>
        </div>
        <button id="menu-btn" class="md:hidden text-white text-2xl p-2"><i class="fa-solid fa-bars"></i></button>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="fixed inset-0 bg-acid z-[60] transform translate-y-full transition-transform duration-500 flex flex-col justify-center p-8">
        <button id="close-menu" class="absolute top-6 right-6 text-black text-4xl p-2"><i class="fa-solid fa-xmark"></i></button>
        <a href="#manifesto" class="text-black font-display font-black text-5xl md:text-6xl mb-6 hover:italic transition-all">MANIFESTO</a>
        <a href="#services" class="text-black font-display font-black text-5xl md:text-6xl mb-6 hover:italic transition-all">SERVIÇOS</a>
        <a href="#contact" class="text-black font-display font-black text-5xl md:text-6xl mb-6 hover:italic transition-all">CONTATO</a>
    </div>

    <!-- Hero Section -->
    <header class="min-h-screen flex flex-col justify-center px-4 md:px-6 pt-20 relative border-b border-white/10">
        <div class="max-w-[1800px] mx-auto w-full">
            <p class="font-mono text-acid mb-4 text-xs md:text-base" data-aos="fade-right">> STATUS: ONLINE</p>
            <!-- Tipografia ajustada para Mobile -->
            <h1 class="font-display font-bold text-[13vw] md:text-[12vw] leading-[0.9] md:leading-[0.8] text-white mix-blend-difference mb-8">
                WE BUILD <br>
                <span class="outline-text">DIGITAL</span> <br>
                FORTRESSES
            </h1>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end pb-12 md:pb-24 gap-8 md:gap-0">
                <p class="font-mono text-gray-400 max-w-md text-sm md:text-base">
                    Não fazemos apenas sites. Nós arquitetamos infraestruturas de elite e desenvolvemos experiências web que dominam o mercado. Segurança militar. Design agressivo.
                </p>
                <a href="#contact" class="bg-acid text-black font-bold font-mono px-6 py-3 md:px-8 md:py-4 text-base md:text-lg hover:bg-white transition-colors flex items-center gap-4 group w-full md:w-auto justify-center">
                    INICIAR SISTEMA <i class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>
        
        <!-- Marquee -->
        <div class="absolute bottom-0 left-0 w-full border-t border-white/10 py-2 bg-acid text-black font-mono text-xs font-bold overflow-hidden marquee">
            <p>
                /// FIREWALL ACTIVE /// WEB DEVELOPMENT /// NETWORK SECURITY /// SERVER MANAGEMENT /// UI/UX DESIGN /// 99.9% UPTIME /// DATA PROTECTION ///
                /// FIREWALL ACTIVE /// WEB DEVELOPMENT /// NETWORK SECURITY /// SERVER MANAGEMENT /// UI/UX DESIGN /// 99.9% UPTIME /// DATA PROTECTION ///
            </p>
        </div>
    </header>

    <!-- Manifesto / About -->
    <section id="manifesto" class="border-b border-white/10">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-8 md:p-24 border-b md:border-b-0 md:border-r border-white/10 flex flex-col justify-between h-auto md:h-full bg-concrete/20 hover:bg-acid hover:text-black transition-colors duration-500 group">
                <i class="fa-solid fa-code text-5xl md:text-6xl mb-6 md:mb-8 text-white group-hover:text-black"></i>
                <div>
                    <h2 class="font-display font-bold text-3xl md:text-4xl mb-4">DEV OPS</h2>
                    <p class="font-mono text-xs md:text-sm opacity-80 leading-relaxed">
                        Código limpo é a nossa religião. Criamos sites e sistemas que carregam em milissegundos e convertem visitantes em receita. Sem templates prontos. Apenas engenharia pura.
                    </p>
                </div>
            </div>
            <div class="p-8 md:p-24 flex flex-col justify-between h-auto md:h-full bg-void hover:bg-acid hover:text-black transition-colors duration-500 group">
                <i class="fa-solid fa-server text-5xl md:text-6xl mb-6 md:mb-8 text-white group-hover:text-black"></i>
                <div>
                    <h2 class="font-display font-bold text-3xl md:text-4xl mb-4">INFRA OPS</h2>
                    <p class="font-mono text-xs md:text-sm opacity-80 leading-relaxed">
                        Sua rede é o seu castelo. Implementamos firewalls, VPNs e monitoramento 24/7. Se o seu servidor cai, nós o levantamos antes que você perceba.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services List (The Stack) -->
    <section id="services" class="py-20 md:py-32 px-4 md:px-6">
        <div class="max-w-[1800px] mx-auto">
            <p class="font-mono text-acid mb-8 md:mb-12 text-sm md:text-base">/// PROTOCOLOS DE SERVIÇO</p>
            
            <div class="space-y-4">
                <!-- Service Item 1 -->
                <div class="service-item border-t border-b border-white/20 py-6 md:py-8 flex flex-col md:flex-row justify-between items-start md:items-center group hover:border-acid transition-colors" data-aos="fade-up">
                    <h3 class="font-display font-black text-2xl sm:text-3xl md:text-6xl text-white group-hover:text-acid group-hover:translate-x-4 transition-all duration-300 break-words">WEB DESIGN</h3>
                    <div class="mt-4 md:mt-0 font-mono text-left md:text-right opacity-50 group-hover:opacity-100 text-xs md:text-base">
                        [ UI/UX ] [ LANDING PAGES ] [ E-COMMERCE ]<br>
                        <span class="text-acid">> HIGH CONVERSION RATE</span>
                    </div>
                </div>

                <!-- Service Item 2 -->
                <div class="service-item border-b border-white/20 py-6 md:py-8 flex flex-col md:flex-row justify-between items-start md:items-center group hover:border-acid transition-colors" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="font-display font-black text-2xl sm:text-3xl md:text-6xl text-white group-hover:text-acid group-hover:translate-x-4 transition-all duration-300 break-words">ADMINISTRAÇÃO</h3>
                    <div class="mt-4 md:mt-0 font-mono text-left md:text-right opacity-50 group-hover:opacity-100 text-xs md:text-base">
                        [ UPDATES ] [ BACKUPS ] [ PERFORMANCE ]<br>
                        <span class="text-acid">> ZERO DOWNTIME</span>
                    </div>
                </div>

                <!-- Service Item 3 -->
                <div class="service-item border-b border-white/20 py-6 md:py-8 flex flex-col md:flex-row justify-between items-start md:items-center group hover:border-acid transition-colors" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="font-display font-black text-2xl sm:text-3xl md:text-6xl text-white group-hover:text-acid group-hover:translate-x-4 transition-all duration-300 break-words">CYBER SECURITY</h3>
                    <div class="mt-4 md:mt-0 font-mono text-left md:text-right opacity-50 group-hover:opacity-100 text-xs md:text-base">
                        [ FIREWALL ] [ VPN ] [ ANTI-DDoS ]<br>
                        <span class="text-acid">> TOTAL PROTECTION</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Marquee -->
    <div class="py-8 md:py-12 border-y border-white/10 overflow-hidden bg-concrete/30">
        <div class="marquee font-display font-bold text-2xl md:text-6xl text-white/10 uppercase">
            <p>
                PHP // LARAVEL // TAILWIND // LINUX // CISCO // PFSENSE // DOCKER // AWS // CLOUDFLARE // WORDPRESS // REACT // 
                PHP // LARAVEL // TAILWIND // LINUX // CISCO // PFSENSE // DOCKER // AWS // CLOUDFLARE // WORDPRESS // REACT //
            </p>
        </div>
    </div>

    <!-- Contact Form (Brutalist) -->
    <section id="contact" class="grid grid-cols-1 lg:grid-cols-2 min-h-screen">
        <div class="bg-acid p-8 md:p-24 flex flex-col justify-center text-black">
            <h2 class="font-display font-black text-4xl sm:text-5xl md:text-8xl mb-6 md:mb-8 leading-[0.85]">VAMOS<br>QUEBRAR<br>PADRÕES.</h2>
            <p class="font-mono text-base md:text-lg mb-8 md:mb-12 border-l-2 border-black pl-6">
                Sua empresa merece mais que o básico. Fale com engenheiros, não com vendedores.
            </p>
            <div class="font-mono text-base md:text-xl space-y-2">
                <p>> EMAIL: guilhermesantiago921@gmail.com</p>
                <p>> WHATSAPP: (11) 91158-2021</p>
                <p>> LOCAL: SÃO PAULO, BR</p>
            </div>
        </div>

        <div class="p-8 md:p-24 flex flex-col justify-center bg-void relative">
            <?php if(!empty($mensagem_envio)): ?>
                <div class="mb-8 p-4 border border-acid text-acid font-mono bg-acid/10 text-sm">
                    > <?php echo $mensagem_envio; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>#contact" method="POST" class="space-y-6 md:space-y-8 font-mono">
                <div class="relative group">
                    <label class="text-xs text-gray-500 block mb-2 group-focus-within:text-acid">01 // IDENTIFICAÇÃO</label>
                    <input type="text" name="nome" required class="w-full bg-transparent border-b border-gray-700 py-3 md:py-4 text-white focus:border-acid focus:outline-none transition-colors text-lg md:text-xl" placeholder="Nome Completo_">
                </div>
                
                <div class="relative group">
                    <label class="text-xs text-gray-500 block mb-2 group-focus-within:text-acid">02 // COMUNICAÇÃO</label>
                    <input type="email" name="email" required class="w-full bg-transparent border-b border-gray-700 py-3 md:py-4 text-white focus:border-acid focus:outline-none transition-colors text-lg md:text-xl" placeholder="Email Corporativo_">
                </div>

                <div class="relative group">
                    <label class="text-xs text-gray-500 block mb-2 group-focus-within:text-acid">03 // FREQUÊNCIA</label>
                    <input type="tel" name="whatsapp" required class="w-full bg-transparent border-b border-gray-700 py-3 md:py-4 text-white focus:border-acid focus:outline-none transition-colors text-lg md:text-xl" placeholder="WhatsApp_">
                </div>

                <div class="relative group">
                    <label class="text-xs text-gray-500 block mb-2 group-focus-within:text-acid">04 // MISSÃO</label>
                    <select name="interesse" class="w-full bg-transparent border-b border-gray-700 py-3 md:py-4 text-white focus:border-acid focus:outline-none transition-colors text-lg md:text-xl appearance-none rounded-none cursor-pointer">
                        <option value="Dev" class="bg-black">Desenvolvimento Web</option>
                        <option value="Admin" class="bg-black">Administração de Site</option>
                        <option value="Infra" class="bg-black">Infraestrutura/Rede</option>
                        <option value="Full" class="bg-black">Solução Híbrida</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-white text-black font-bold font-display text-xl md:text-2xl py-5 md:py-6 hover:bg-acid transition-colors border border-transparent hover:border-black mt-8">
                    EXECUTAR PROTOCOLO >
                </button>
            </form>
        </div>
    </section>

    <footer class="bg-black text-gray-600 font-mono text-[10px] md:text-xs py-8 px-6 flex flex-col md:flex-row justify-between items-center gap-4 border-t border-white/10">
        <span>© 2026 INFRAWEB SOLUTIONS</span>
        <span>SYSTEM VERSION 2.0.4</span>
    </footer>

    <!-- JS Logic -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Init Animation Library
        AOS.init({ duration: 1000, once: true });

        // Custom Cursor Logic (Only for Desktop)
        const cursor = document.getElementById('cursor');
        
        // Check if device has mouse support
        if (window.matchMedia("(pointer: fine)").matches) {
            document.addEventListener('mousemove', (e) => {
                cursor.style.left = e.clientX - 10 + 'px';
                cursor.style.top = e.clientY - 10 + 'px';
            });

            // Hover Effect on Links grows cursor
            const interactiveElements = document.querySelectorAll('a, button, input, select, .service-item');
            interactiveElements.forEach(el => {
                el.addEventListener('mouseenter', () => {
                    cursor.style.transform = 'scale(4)';
                    cursor.style.background = '#fff';
                });
                el.addEventListener('mouseleave', () => {
                    cursor.style.transform = 'scale(1)';
                    cursor.style.background = '#ccff00';
                });
            });
        }

        // Preloader Logic
        const loaderBar = document.getElementById('loader-bar');
        const preloader = document.getElementById('preloader');
        
        let width = 0;
        const interval = setInterval(() => {
            if (width >= 100) {
                clearInterval(interval);
                preloader.style.opacity = '0';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            } else {
                width++;
                loaderBar.style.width = width + '%';
            }
        }, 15); // Adjust speed here

        // Mobile Menu
        const menuBtn = document.getElementById('menu-btn');
        const closeMenu = document.getElementById('close-menu');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileLinks = document.querySelectorAll('#mobile-menu a');

        function toggleMenu() {
            mobileMenu.classList.toggle('translate-y-full');
            // Prevent scroll when menu is open
            if (mobileMenu.classList.contains('translate-y-full')) {
                document.body.style.overflow = 'auto';
            } else {
                document.body.style.overflow = 'hidden';
            }
        }

        menuBtn.addEventListener('click', toggleMenu);
        closeMenu.addEventListener('click', toggleMenu);
        mobileLinks.forEach(l => l.addEventListener('click', toggleMenu));

    </script>
</body>
</html>