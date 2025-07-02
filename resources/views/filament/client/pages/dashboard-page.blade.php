<x-filament-panels::page>
<link rel="preload" as="image" href="{{ asset('client-imgs/BACK.jpg') }}">
<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .backgroundModal{
       background-image: url({{asset('client-imgs/BACK.jpg') }});
         background-size: cover;
        background-position: center;
    }


    .modal-card {
        background-color: #1e1e1e;
        padding: 2rem;
        border-radius: 10px;
        max-width: 500px;
        width: 100%;
        color: white;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
    }

    .hidden {
        display: none;
    }

    body.modal-open {
        overflow: hidden;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-animation {
        animation: modalFadeIn 0.3s ease-out;
    }

    .modal-shadow {
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
    }
    .x-button {
        font-size: 30px !important;
    }
</style>

<style>
    /* Responsive styles for mobile (max-width: 767px) */
    @media (max-width: 767px) {
        /* Buy/Sell buttons and input number */
        .buy-sell-group button {
            padding: 6px 10px !important;
            font-size: 13px !important;
            min-width: 60px !important;
        }
        .buy-sell-group input[type="number"] {
            width: 90px !important;
            padding: 4px 6px !important;
            font-size: 13px !important;
        }
        /* Retiro y Deposito buttons */
        .action-buttons {
            display: flex !important;
            flex-direction: column !important;
            gap: 8px !important;
            align-items: stretch !important;
        }
        .action-buttons a,
        .action-buttons button {
            padding: 7px 10px !important;
            font-size: 13px !important;
            min-width: 60px !important;
            margin-left: 0 !important;
            width: 100% !important;
            text-align: center !important;
        }
        /* Modal dialog texts */
        dialog .modal-title,
        dialog .modal-title h2,
        dialog .modal-title p,
        dialog .modal-title span,
        dialog .modal-title label,
        dialog .modal-title div,
        dialog .modal-title {
            font-size: 15px !important;
        }
        dialog .modal-title .x-button{
            font-size: 30px !important;
        }

        dialog .modal-title p,
        dialog .modal-title .modal-text,
        dialog .modal-title, {
            font-size: 13px !important;
        }
        dialog .modal-title {
            padding: 10px !important;
        }
        /* Cartera en modal */
        .cartera {
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 8px !important;
        }
        .cartera .cartera-form {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
        }
        .cartera .cartera-form label {
            display: block !important;
            text-align: center !important;
            margin-bottom: 4px !important;
        }
        .cartera #copy-button {
            margin-left: 0 !important;
        }
    }
</style>
</style>

<div id="modal" class="modal-overlay hidden">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="font-size: 1.2rem;">Operación</h2>
            <button onclick="toggleModal1()" style="color: white; background: none;  font-size: 1.2rem;">✖</button>
        </div>
        <p>Dirigete a tu app de trading</p>
    </div>
</div>

    <!-- Modal -->
    <dialog id="myModal" class="fixed top-0 left-0 w-full h-full bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="modal-title py-4  px-6 bg-slate-900 text-white rounded-lg w-[90%] max-w-2xl relative border-2 border-white modal-animation modal-shadow">
            <hr class="border-2 border-slate-300 w-full">

            <div class="flex flex-col items-center py-3">
                <p class="text-xl text-slate-300 uppercase font-bold">CARTERA RECEPTORA</p>

                <div class="text-black flex w-full my-4 cartera">
                    <div class="w-full cartera-form">
                        <label for="walled-address" class="font-semibold bg-slate-300 border-2 rounded-md border-slate-300 py-2 px-2 ">Billetera FR</label>
                        <input
                            type="text"
                            id="wallet-address"
                            value="TAGAEYogs4ZiAc1Fu5Bf3Mjbjjz2TYFj1i"
                            class="border-2 border-slate-300 bg-slate-300 rounded-md w-[80%] text-center py-2"
                            readonly
                        >
                    </div>
                    <div
                        id="copy-button"
                        class="border-2 w-fit border-slate-300 rounded-md bg-slate-300 px-2 py-2 ml-4 cursor-pointer"
                        onclick="copyToClipboard()"
                    >
                        📋
                    </div>
                </div>
                <div class="py-2 px-3 bg-slate-300 rounded-md border-2 mb-4 border-slate-300">
                    <p class="text-black font-bold ">USDT: Thether(USDT-Trc20)</p>
                </div>

                <div class="bg-slate-300 border-4 border-slate-300 rounded-lg text-black py-4 px-3">
                    <p class="text-center font-medium">
                        Con el código dirígete a una de estas páginas de pago y con tu comprobante de pago continua el depósito
                    </p>
                    <div class="flex justify-between px-2 md:px-[100px]">
                        <a class="text-sky-500 hover:text-sky-800 font-semibold" target="_blank" href="https://buy.simplex.com/">
                            Enlace a Simplex
                        </a>
                        <a class="text-sky-500 hover:text-sky-800 font-semibold" target="_blank" href="https://openocean.banxa.com/">
                            Enlace a Banxa
                        </a>
                    </div>
                </div>
                <div class="">
                    <button onclick="toggleModal2()" class="py2 px-3 mt-1 font-semibold bg-slate-800 hover:bg-slate-600 border-2 border-slate-300 rounded-md">salir</button>
                </div>
            </div>

            <!-- Botón para cerrar -->
            <button onclick="toggleModal2()" class="absolute text-3xl top-2 right-2 text-white font-bold x-button">
                x
            </button>

            <hr class="border-2 border-slate-300 w-full mt-1">
        </div>
    </dialog>

    {{-- TRADING VIEW GENERAL CHART --}}
    <div style="display: flex; flex-direction: column; min-min-height: 500px; ">
        <div class="flex justify-between  w-full mb-4">
            <div class="buy-sell-group" style="display: flex; align-items: start ; gap: 10px;">
                <div class="">
                    <button onclick="toggleModal1()" style="padding: 10px 15px; background-color: green; border: 2px solid rgba(211, 211, 211, 0.86); color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);  border-top-left-radius: 5px;">
                        Buy
                    </button>
                    <input type="number" placeholder="0.00"
                        style="height: 100%; padding-inline: 7px; padding-block: 11px; border: 2px solid rgba(211, 211, 211, 0.86); color: black; background-color: transparent;width: 150px"
                        onfocus="this.placeholder = ''" onblur="this.placeholder = '0.00'">
                    <button onclick="toggleModal1()" style="padding: 10px 15px; background-color: red; border: 2px solid rgba(211, 211, 211, 0.86); color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);  border-top-right-radius: 5px;">Sell</button>
                </div>
            </div>
            <div class="action-buttons">
                <a  href="https://crm.frspot.com/client/movimientos/create" style="padding: 12px 15px; background-color: #0F172A ; border: 2px solid rgba(211, 211, 211, 0.86); color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); border-radius: 5px;">
                    Retiro
                </a>
                <button onclick="toggleModal2()" style="padding: 10px 15px; background-color: green; border: 2px solid rgba(211, 211, 211, 0.86); color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); margin-left: 10px; border-radius: 5px;">
                    Deposito
                </button>
            </div>
        </div>
        <div style="flex: 1; min-height: 500px; width: 100%;">
            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container" style="min-height: 500px; width: 100%;">
                <div class="tradingview-widget-container__widget" style="height: 100%; width: 100%;"></div>
                <div class="tradingview-widget-copyright">
                    <a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank">
                        <span class="blue-text">Track all markets on TradingView</span>
                    </a>
                </div>
                <script type="text/javascript"
                    src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
                    {
                        "autosize": true,
                        "symbol": "NASDAQ:AAPL",
                        "interval": "D",
                        "timezone": "Etc/UTC",
                        "theme": "dark",
                        "style": "1",
                        "locale": "en",
                        "allow_symbol_change": true,
                        "support_host": "https://www.tradingview.com"
                    }
                </script>
            </div>
            <!-- TradingView Widget END -->
        </div>
    </div>

    {{-- TRADING VIEW CRYPTO --}}
    <div style="display: flex; flex-direction: column; min-min-height: 500px;">
        <div class="flex justify-between  w-full mb-4">
            <div class="buy-sell-group" style="display: flex; align-items: start ; gap: 10px;">
                <div class="">
                    <button onclick="toggleModal1()" style="padding: 10px 15px; background-color: green; border: 2px solid rgba(211, 211, 211, 0.86); color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);  border-top-left-radius: 5px;">
                        Buy
                    </button>
                    <input type="number" placeholder="0.00"
                        style="height: 100%; padding-inline: 7px; padding-block: 11px; border: 2px solid rgba(211, 211, 211, 0.86); color: black; background-color: transparent;width: 150px"
                        onfocus="this.placeholder = ''" onblur="this.placeholder = '0.00'">
                    <button onclick="toggleModal1()" style="padding: 10px 15px; background-color: red; border: 2px solid rgba(211, 211, 211, 0.86); color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);  border-top-right-radius: 5px;">Sell</button>
                </div>
            </div>
            <div class="action-buttons">
                <a  href="https://crm.frspot.com/client/movimientos/create" style="padding: 12px 15px; background-color: #0F172A ; border: 2px solid rgba(211, 211, 211, 0.86); color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); border-radius: 5px;">
                    Retiro
                </a>
                <button onclick="toggleModal2()" style="padding: 10px 15px; background-color: green; border: 2px solid rgba(211, 211, 211, 0.86); color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); margin-left: 10px; border-radius: 5px;">
                    Deposito
                </button>
            </div>
        </div>

        <div style="flex: 1; min-min-height: 500px; width: 100%;">
            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container" style="min-height: 500px; width: 100%;">
                <div class="tradingview-widget-container__widget" style="height: 100%; width: 100%;"></div>
                <div class="tradingview-widget-copyright">
                    <a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank">
                        <span class="blue-text">Track all markets on TradingView</span>
                    </a>
                </div>
                <script type="text/javascript"
                    src="https://s3.tradingview.com/external-embedding/embed-widget-crypto-coins-heatmap.js" async>
                    {
                        "dataSource": "Crypto",
                        "blockSize": "market_cap_calc",
                        "blockColor": "24h_close_change|5",
                        "locale": "en",
                        "symbolUrl": "",
                        "colorTheme": "dark",
                        "hasTopBar": false,
                        "isDataSetEnabled": false,
                        "isZoomEnabled": true,
                        "hasSymbolTooltip": true,
                        "isMonoSize": false,
                        "width": "100%",
                        "height": "100%"
                    }
                </script>
            </div>
            <!-- TradingView Widget END -->
        </div>
    </div>

    {{-- Modal 1 --}}
    <script>
        function toggleModal1() {
            const modal = document.getElementById('modal');
            const isHidden = modal.classList.contains('hidden');

            if (isHidden) {
                modal.classList.remove('hidden');
                document.body.classList.add('modal-open');
            } else {
                modal.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }
        }
    </script>

    {{-- Modal 2 --}}
    <script>
        function toggleModal2() {
            const modal = document.getElementById('myModal');
            const isOpen = !modal.classList.contains('hidden');

            if (isOpen) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            } else {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function copyToClipboard() {
            const input = document.getElementById('wallet-address');
            input.select();
            input.setSelectionRange(0, 99999); // Para móviles

            document.execCommand("copy");

            alert("Dirección copiada: " + input.value);
        }
    </script>

</x-filament-panels::page>
