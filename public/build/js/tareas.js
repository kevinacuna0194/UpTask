!function(){function e(e,t,a){const n=document.querySelector(".alerta");n&&n.remove();const o=document.createElement("DIV");o.classList.add("alerta",t),o.textContent=e,a.parentElement.insertBefore(o,a.nextElementSibling),setTimeout(()=>{o.remove()},5e3)}document.querySelector("#agregar-tarea").addEventListener("click",(function(){const t=document.createElement("DIV");t.classList.add("modal"),t.innerHTML='\n            <form class="formulario nueva-tarea">\n                <legend>Añade una nueva tarea</legend>\n                <div class="campo">\n                    <label for="tarea">Tarea</label>\n                    <input\n                        type="text"\n                        name="tarea"\n                        id="tarea"\n                        placeholder="Añadir Tarea al Proyecto Actual"\n                    />\n                </div>\n                <div class="opciones">\n                    <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea"/>\n                    <button type="button" class="cerrar-modal">Cancelar</button>\n                </div>\n            </form>\n            ',setTimeout(()=>{document.querySelector(".formulario").classList.add("animar")},0),t.addEventListener("click",(function(a){if(a.preventDefault(),a.target.classList.contains("cerrar-modal")){document.querySelector(".formulario").classList.add("cerrar"),setTimeout(()=>{t.remove()},500)}a.target.classList.contains("submit-nueva-tarea")&&function(){const t=document.querySelector("#tarea").value.trim();if(""===t)return void e("El Nombre de la tarea es obligatorio","error",document.querySelector(".formulario legend"));!async function(t){const a=new FormData;a.append("nombre",t),a.append("proyectoId",function(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).url}());try{const t="http://localhost:3000/api/tarea",n=await fetch(t,{method:"POST",body:a}),o=await n.json();if(console.log(o),e(o.mensaje,o.tipo,document.querySelector(".formulario legend")),"exito"===o.tipo){const e=document.querySelector(".modal");setTimeout(()=>{e.remove()},3e3)}}catch(e){console.log(e)}}(t)}()})),document.querySelector(".dashboard").appendChild(t)}))}();
//# sourceMappingURL=tareas.js.map
