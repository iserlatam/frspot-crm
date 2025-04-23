<x-filament-panels::page>

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
</style>

<div id="modal" class="modal-overlay hidden">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="font-size: 1.2rem;">Operación</h2>
            <button onclick="toggleModal()" style="color: white; background: none; border: none; font-size: 1.2rem;">✖</button>
        </div>
        <p>Aquí puedes colocar la información de compra o venta.</p>
    </div>
</div>

    {{-- TRADING VIEW GENERAL CHART --}}
    <div style="display: flex; flex-direction: column; min-min-height: 500px;">
        <div style="flex: 1;">
            <div style="display: flex; align-items: start">
                <button onclick="toggleModal()" style="padding: 10px 15px; background-color: green; color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); border: none; border-top-left-radius: 5px;">
                    Buy
                </button>
                <input type="text" placeholder="Quantity"
                    style="height: 100%; padding-inline: 7px; padding-block: 11px; border: 2px solid rgba(211, 211, 211, 0.86); color: white; background-color: transparent;width: 150px"
                    onfocus="this.placeholder = ''" onblur="this.placeholder = 'Quantity'">
                <button onclick="toggleModal()" style="padding: 10px 15px; background-color: red; color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); border: none; border-top-right-radius: 5px;">Sell</button>
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
        <div style="flex: 1;">
            <div style="display: flex; align-items: start">
                <button onclick="toggleModal()" style="padding: 10px 15px; background-color: green; color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); border: none; border-top-left-radius: 5px;">
                    Buy
                </button>
                <input type="text" placeholder="Quantity"
                    style="height: 100%; padding-inline: 7px; padding-block: 11px; border: 2px solid rgba(211, 211, 211, 0.86); color: white; background-color: transparent;width: 150px"
                    onfocus="this.placeholder = ''" onblur="this.placeholder = 'Quantity'">
                <button onclick="toggleModal()" style="padding: 10px 15px; background-color: red; color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); border: none; border-top-right-radius: 5px;">Sell</button>
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

    <script>
        function toggleModal() {
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

</x-filament-panels::page>
