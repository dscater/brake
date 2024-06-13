import { useConfiguracionStore } from "@/stores/configuracion/configuracionStore";
import axios from "axios";
import { storeToRefs } from "pinia";
import { onMounted } from "vue";

export const useConfiguracion = () => {
    const store_configuracion = useConfiguracionStore();
    const { oConfiguracion } = storeToRefs(store_configuracion);

    const getConfiguracion = async (id) => {
        try {
            const response = await axios.get(
                route("configuracions.getConfiguracion", id),
                {
                    headers: { Accept: "application/json" },
                }
            );
            store_configuracion.setInstiticion(response.data.configuracion);
            return response.data.configuracion;
        } catch (error) {
            console.error("Error al obtener la institución:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    onMounted(() => {
        getConfiguracion();
    });

    return {
        // propiedades
        oConfiguracion,
        // metodos
        getConfiguracion,
    };
};
