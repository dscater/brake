import { defineStore } from "pinia";

export const useConfiguracionStore = defineStore("configuracion", {
    state: () => ({
        oConfiguracion: {
            nombre_sistema: "BRAKE",
            alias: "SO",
            razon_social: "BRAKE S.A.",
            nit: "111111",
            ciudad: "LA PAZ",
            dir: "LOS OLIVOS",
            fono: "777777",
            correo: "BRAKE@GMAIL.COM",
            web: "",
            actividad: "ACTIVIDAD",
            logo: "logo.jpg",
            // appends
            iniciales_nombre: "",
            url_logo: "",
        },
    }),
    actions: {
        setInstiticion(value) {
            this.oConfiguracion = value;
        },
    },
});
