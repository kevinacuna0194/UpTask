<div class="contenedor">
    <h1>UpTask</h1>
    <p>Crea y Administra tus Proyectos</p>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <form class="formulario" action="/" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    placeholder="Tu Email" 
                    name="email"
                />
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    placeholder="Tu Password" 
                    name="password"
                />
            </div>

            <input type="submit" class='boton' value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una</a>
            <a href="/olvide">¿Olvidaste tu Password?</a>
        </div>

    </div> <!-- contenedor-sm -->
</div> <!-- contenedor -->