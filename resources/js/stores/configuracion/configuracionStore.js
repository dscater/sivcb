import { defineStore } from "pinia";

export const useConfiguracionStore = defineStore("configuracion", {
    state: () => ({
        oConfiguracion: {
            nombre_sistema:"SIVCB",
            alias:"SJ",
            razon_social:"SIVCB S.A.",
            ciudad:"",
            dir:"",
            fono:"",
            correo:"",
            web:"",
            actividad:"",
            // appends
            url_logo: "",
        },
    }),
    actions: {
        setConfiguracion(value) {
            this.oConfiguracion = value;
        },
    },
});
