<x-filament-panels::page>

    {{-- TRADING VIEW GENERAL CHART --}}
    <div style="display: flex; flex-direction: column; height: fit-content;">
        <div style="flex: 1;">
            <!-- Column 1 content -->
            <div style="display: flex; align-items: start">
                <button
                    style=
                        "padding: 10px 15px; background-color: green; color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); border: none; border-top-left-radius: 5px;">
                    Buy
                </button>
                <input type="text" placeholder="Quantity"
                    style="height: 100%; padding-inline: 7px; padding-block: 11px; border: 2px solid rgba(211, 211, 211, 0.86); color: white; background-color: transparent;width: 150px"
                    onfocus="this.placeholder = ''" onblur="this.placeholder = 'Quantity'"
                    placeholder-style="color: snow;">
                <button
                    style="padding: 10px 15px; background-color: red; color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); border: none; border-top-right-radius: 5px;">Sell</button>
            </div>
        </div>
        <div style="flex: 1;">
            <!-- Column 2 content -->
            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container" style="height:100%;width:100%">
                <div class="tradingview-widget-container__widget" style="height:calc(100% - 32px);width:100%"></div>
                <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow"
                        target="_blank"><span class="blue-text">Track all markets on TradingView</span></a></div>
                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
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
    <div style="display: flex; flex-direction: column; height: fit-content;">
        <div style="flex: 1;">
            <!-- Column 1 content -->
            <div style="display: flex; align-items: start">
                <button
                    style=
                        "padding: 10px 15px; background-color: green; color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); border: none; border-top-left-radius: 5px;">
                    Buy
                </button>
                <input type="text" placeholder="Quantity"
                    style="height: 100%; padding-inline: 7px; padding-block: 11px; border: 2px solid rgba(211, 211, 211, 0.86); color: white; background-color: transparent;width: 150px"
                    onfocus="this.placeholder = ''" onblur="this.placeholder = 'Quantity'"
                    placeholder-style="color: snow;">
                <button
                    style="padding: 10px 15px; background-color: red; color: white; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2); border: none; border-top-right-radius: 5px;">Sell</button>
            </div>
        </div>
        <div style="flex: 1;">
            <!-- Column 2 content -->
            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container">
                <div class="tradingview-widget-container__widget"></div>
                <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow"
                        target="_blank"><span class="blue-text">Track all markets on TradingView</span></a></div>
                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-crypto-coins-heatmap.js"
                    async>
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


</x-filament-panels::page>
