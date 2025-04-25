<x-filament-panels::page>

    <div class="bg-gray-300 w-full h-full " style="padding-inline: 20px; border-radius: 20px; padding-bottom: 20px;">

        <!-- Seccion 1 -->
        <div class=" ">
            <h2 class="mt-10 text-center text-4xl uppercase my-2 font-bold">
                Explicación de los indicadores de trading
            </h2>
            <p class="text-lg font-medium my-5 text-justify">
                Si le interesa el trading de forex, trading de materias primas o trading
                de acciones, puede ser útil utilizar el análisis técnico como parte de
                su estrategia, lo que incluye el estudio de varios indicadores de
                trading. Los indicadores de trading son cálculos matemáticos que se
                representan como líneas en un gráfico de precios y pueden ayudar a los
                inversores a identificar ciertas señales y tendencias dentro del
                mercado.
            </p>

            <p class="text-lg font-medium my-5 text-justify">
                Hay diferentes tipos de indicadores de trading, incluidos los
                indicadores adelantados (leading) y los indicadores atrasados (lagging).
                Un indicador adelantado es una señal de pronóstico que predice los
                movimientos futuros de los precios, mientras que un indicador atrasado
                observa las tendencias pasadas e indica el impulso.
            </p>
        </div>

        <div class="text-center">
            <h3 class="text-2xl uppercase font-semibold">
                Los 10 mejores indicadores de trading
            </h3>
            <ul class="mt-4 md:w-max mx-auto text-center">
                <li class="font-bold ">Media móvil (MA)</li>
                <li class="font-bold ">Media móvil exponencial (EMA)</li>
                <li class="font-bold ">Oscilador estocástico</li>
                <li class="font-bold ">Convergencia/divergencia de medias móviles (MACD)</li>
                <li class="font-bold ">Bandas de Bollinger</li>
                <li class="font-bold ">Índice de fuerza relativo (RSI)</li>
                <li class="font-bold ">Retrocesos de Fibonacci</li>
                <li class="font-bold ">Nube de Ichimoku</li>
                <li class="font-bold ">Desviación estándar</li>
                <li class="font-bold ">Índice de promedio direccional</li>
            </ul>
        </div>

        <p class="text-lg font-medium my-5 text-justify">
            Puede utilizar sus conocimientos y su apetito por el riesgo como medida
            para decidir cuál de estos indicadores de trading se adapta mejor a su
            estrategia. Tenga en cuenta que los indicadores indicados aquí no aparecen
            por orden de importancia, pero sí son algunas de las opciones más
            populares para los inversores minoristas.
        </p>
        <!-- Seccuion 2 -->
        <h2 class="mt-10 text-center text-4xl uppercase my-2 font-bold">
            Media móvil (MA)
        </h2>

        <p class="text-lg font-medium my-5 text-justify">
            La MA o media móvil simple (SMA) es un indicador utilizado para
            identificar la dirección de una tendencia de precios actuales, sin la
            interferencia de picos de precios a más corto plazo. El indicador MA
            combina los precios de un instrumento financiero a lo largo de un periodo
            de tiempo establecido y los divide entre el número de datos recogidos para
            dar una línea de tendencia.
        </p>

        <p class="text-lg font-medium my-5 text-justify">
            Los datos utilizados dependen de la longitud de la MA. Por ejemplo, una MA
            de 200 días requiere 200 días de datos. Utilizando el indicador MA, puede
            estudiar los niveles de soporte y resistencia y ver la acción previa de
            los precios (la historia del mercado), lo que significa que también puede
            determinar posibles patrones futuros.
        </p>
        <div class="">
            <h3 class="text-2xl uppercase text-center font-bold mb-5">Media móvil</h3>

            <img src="{{ asset('client-imgs/indi1.png') }}" alt="indicador ejemplo 1" class="md:w-[70%] mx-auto" />
        </div>
        <!-- seccion 3 -->
        <h2 class="mt-10 text-center text-4xl uppercase my-2 font-bold">
            Media móvil exponencial (EMA)
        </h2>

        <p class="text-lg font-medium my-5 text-justify">
            La EMA es otra forma de media móvil. A diferencia de la SMA, da más peso a
            los puntos de datos recientes, lo que hace que los datos respondan mejor a
            la nueva información. Cuando se utiliza con otros indicadores, las EMA
            pueden ayudar a los inversores a confirmar movimientos significativos del
            mercado y valorar su legitimidad.
        </p>

        <p class="text-lg font-medium my-5 text-justify">
            Las medias móviles exponenciales más populares son las EMA de 12 y 26 días
            para las medias a corto plazo, mientras que las EMA de 50 y 200 días se
            utilizan como indicadores de tendencias a largo plazo.
        </p>

        <div class="">
            <h2 class="text-2xl uppercase text-center font-bold mb-5">
                Media móvil exponencial
            </h2>

            <img src="{{ asset('client-imgs/indi2.png') }}" alt="indicador 2 ejemlo" class="md:w-[70%] mx-auto" />
        </div>
        <!-- Seccion 4 -->
        <h2 class="mt-10 text-center text-4xl uppercase my-2 font-bold">
            Oscilador estocástico
        </h2>

        <p class="text-lg font-medium my-5 text-justify">
            Un oscilador estocástico es un indicador que compara un precio de cierre
            específico de un activo con un rango de sus precios a lo largo del tiempo,
            y muestra así la fuerza de la tendencia y el impulso. Utiliza una escala
            de 0 a 100. Una lectura por debajo de 20 generalmente representa un
            mercado sobrevendido y una lectura por encima de 80 un mercado
            sobrecomprado. Sin embargo, si hay una tendencia fuerte, no necesariamente
            se producirá una corrección o un rally.
        </p>

        <div class="">
            <h3 class="text-2xl text-center uppercase font-bold mb-3">
                Oscilador estocástico
            </h3>

            <img src="{{ asset('client-imgs/indi3.png') }}" alt="indicador 3 ejemplo" class="md:w-[70%] mx-auto" />
        </div>
        <!-- Seccion 5 -->
        <h2 class="mt-10 text-center text-4xl uppercase my-2 font-bold">
            Media móvil de convergencia/divergencia (MACD)
        </h2>

        <p class="text-lg font-medium my-5 text-justify">
            La MACD es un indicador que detecta cambios en el impulso comparando dos
            medias móviles. Puede ayudar a los inversores a identificar posibles
            oportunidades de compra y venta en torno a los niveles de soporte y
            resistencia.
        </p>

        <p class="text-lg font-medium my-5 text-justify">
            Convergencia significa que dos medias móviles se están acercando, mientras
            que divergencia significa que se están alejando la una de la otra. Si las
            medias móviles convergen, significa que el impulso está disminuyendo,
            mientras que, si las medias móviles son divergentes, el impulso está
            aumentando.
        </p>

        <div class="">
            <h2 class="text-2xl text-center uppercase font-bold mb-3">
                Media móvil de convergencia/divergencia
            </h2>

            <img src="{{ asset('client-imgs/indi4.png') }}" alt="indicador 4 ejemplo" class="md:w-[70%] mx-auto" />
        </div>
        <!-- Seccion 6 -->
        <h2 class="mt-10 text-center text-4xl uppercase my-2 font-bold">
            Bandas de Bollinger
        </h2>

        <p class="text-lg font-medium my-5 text-justify">
            Una banda de Bollinger es un indicador que proporciona un rango dentro del
            cual el precio de un activo normalmente se negocia. El ancho de la banda
            aumenta y disminuye para reflejar la volatilidad reciente. Cuanto más
            próximas entre sí estén las bandas, o cuanto más estrechas sean, menor
            será la volatilidad percibida del instrumento financiero. Cuanto más
            anchas sean las bandas, mayor será la volatilidad percibida.
        </p>

        <p class="text-lg font-medium my-5 text-justify">
            Las bandas de Bollinger son útiles para reconocer cuándo se está
            negociando un activo fuera de sus niveles habituales y se utilizan
            principalmente como método para predecir los movimientos de precios a
            largo plazo. Cuando un precio se mueve continuamente fuera de los
            parámetros superiores de la banda, puede estar sobrecomprado, y cuando se
            mueve por debajo de la banda inferior, puede estar sobrevendido.
        </p>

        <div class="">
            <h2 class="text-2xl text-center uppercase font-bold mb-3">
                Bandas de Bollinger
            </h2>

            <img src="{{ asset('client-imgs/indi5.png') }}" alt="indicador 5 ejemplo" class="md:w-[70%] mx-auto" />
        </div>
        <!-- Secicon 7 -->
        <h2 class="mt-10 text-center text-4xl uppercase my-2 font-bold">
            Índice de fuerza relativa (RSI)
        </h2>

        <p class="text-lg font-medium my-5 text-justify">
            El RSI se utiliza principalmente para ayudar a los inversores a
            identificar el impulso, las condiciones del mercado y las señales de
            advertencia de movimientos peligrosos de los precios. El RSI se expresa
            como una cifra entre 0 y 100. Un activo en torno al nivel 70 se considera
            a menudo sobrecomprado, mientras que un activo en o cerca de 30 se
            considera a menudo sobrevendido.
        </p>

        <p class="text-lg font-medium my-5 text-justify">
            Una señal de sobrecompra sugiere que las ganancias a corto plazo podrían
            estar alcanzando un punto de vencimiento y que los activos podrían estar
            sujetos a una corrección de precios. Por el contrario, una señal de
            sobreventa podría significar que los descensos a corto plazo están
            llegando a su vencimiento y que los activos podrían estar sujetos a un
            rally.
        </p>

        <div class="">
            <h3 class="text-center uppercase text-2xl font-bold mb-3">
                Índice de fuerza relativa
            </h3>

            <img src="{{ asset('client-imgs/indi6.png') }}" alt="indicador 6 ejemplo" class="md:w-[70%] mx-auto" />
        </div>
        <!-- Seccion 8 -->
        <h2 class="mt-10 text-center text-4xl uppercase my-2 font-bold">
            Retrocesos de Fibonacci
        </h2>

        <p class="text-lg font-medium my-5 text-justify">
            Los retrocesos de Fibonacci son un indicador para identificar el grado en
            el que un mercado se moverá en contra de su tendencia actual. Un retroceso
            se da cuando el mercado experimenta una caída temporal. También se
            denomina pullback o desaceleración.
        </p>

        <p class="text-lg font-medium my-5 text-justify">
            Los inversores que piensan que el mercado está a punto de moverse suelen
            utilizar los retrocesos de Fibonacci para confirmarlo, ya que les ayudan a
            identificar los posibles niveles de soporte y resistencia que podrían
            indicar una tendencia al alza o a la baja. Así, deciden dónde aplicar los
            stops y límites, o cuándo abrir y cerrar sus posiciones.
        </p>

        <div class="">
            <h3 class="text-2xl text-center uppercase font-bold mb-3">
                Retrocesos de Fibonacci
            </h3>
            <img src="{{ asset('client-imgs/indi7.png') }}" alt="indicador 7 ejemplo" class="md:w-[70%] mx-auto" />
        </div>
        <!-- Seccion 9 -->
        <h2 class="mt-10 text-center text-4xl uppercase my-2 font-bold">
            Nube de Ichimoku
        </h2>

        <p class="text-lg font-medium my-5 text-justify">
            La nube de Ichimoku, como muchos otros indicadores técnicos, identifica
            los niveles de soporte y resistencia. Sin embargo, también estima el
            impulso de los precios y proporciona a los inversores señales que les
            ayudan a tomar decisiones. La traducción de Ichimoku es gráfico de
            equilibrio de un vistazo, que es exactamente por lo que este indicador lo
            utilizan inversores que necesitan mucha información en un solo gráfico.
        </p>

        <p class="text-lg font-medium my-5 text-justify">
            En pocas palabras, la nube de Ichimoku identifica las tendencias del
            mercado, muestra los niveles actuales de soporte y resistencia y
            pronostica los niveles futuros.
        </p>

        <div class="">
            <h3 class="text-2xl uppercase text-center font-bold mb-3">
                Nube de Ichimoku
            </h3>
            <img src="{{ asset('client-imgs/indi8.png') }}" alt="indicador 8 ejemplo" class="md:w-[70%] mx-auto" />
        </div>
        <!-- Seccion 10  -->
        <h2 class="mt-10 text-center text-4xl uppercase my-2 font-bold">
            Desviación estándar
        </h2>

        <p class="text-lg font-medium my-5 text-justify">
            La desviación estándar es un indicador que ayuda a los inversores a medir
            el tamaño de los movimientos de los precios. En consecuencia, pueden
            identificar la probabilidad de que la volatilidad afecte al precio en el
            futuro. No puede predecir si el precio subirá o bajará, solo que se verá
            afectado por la volatilidad.
        </p>

        <p class="text-lg font-medium my-5 text-justify">
            La desviación estándar compara los movimientos de los precios actuales con
            los movimientos de los precios históricos. Muchos inversores creen que los
            grandes movimientos de precios siguen a los pequeños, y que los pequeños
            movimientos de precios siguen a los grandes movimientos de precios.
        </p>

        <div class="">
            <h3 class="text-2xl text-center uppercase font-bold mb-3">
                Desviación estándar
            </h3>
            <img src="{{ asset('client-imgs/indi9.png') }}" alt=" indicador 9 ejemplo" class="md:w-[70%] mx-auto" />
        </div>
        <!-- Seccion 11 -->
        <h2 class="mt-10 text-center text-4xl uppercase my-2 font-bold">
            Índice de movimiento direccional medio (ADX)
        </h2>
        <p class="text-lg font-medium my-5 text-justify">
            El ADX ilustra la fuerza de una tendencia de precios. Funciona en una
            escala de 0 a 100, donde una lectura de más de 25 se considera una
            tendencia fuerte y un número por debajo de 25 se considera una deriva. Los
            inversores pueden utilizar esta información para determinar si es probable
            que continúe una tendencia alcista o bajista.
        </p>

        <p class="text-lg font-medium my-5 text-justify">
            El ADX se basa normalmente en una media móvil del rango de precios de 14
            días, aunque depende de la frecuencia que los inversores prefieran. Tenga
            en cuenta que el ADX no muestra nunca cómo podría desarrollarse una
            tendencia de precios, simplemente indica la fuerza de la tendencia. El
            índice de promedio direccional puede subir cuando un precio está bajando,
            lo que indica una fuerte tendencia a la baja.
        </p>
        <div class="">
            <h3 class="text-xl text-center uppercase font-bold">
                Índice de movimiento direccional medio (ADX)
            </h3>
            <img src="{{ asset('client-imgs/indi10.png') }}" alt="indice 10 ejemplo" class="w-[50%] mx-auto" />
        </div>
    </div>
</x-filament-panels::page>
