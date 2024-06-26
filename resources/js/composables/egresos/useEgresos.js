import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oEgreso = reactive({
    id: 0,
    fecha: "",
    categoria_id: null,
    egreso_detalles: reactive([]),
    eliminados: reactive([]),
    total_c: 0,
    total_m: 0,
    total_m_txt: "0.00",
    _method: "POST",
});

export const useEgresos = () => {
    const { flash } = usePage().props;
    const getEgresos = async (data) => {
        try {
            const response = await axios.get(route("egresos.listado"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.egresos;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const getEgresosApi = async (data) => {
        try {
            const response = await axios.get(route("egresos.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.egresos;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };
    const saveEgreso = async (data) => {
        try {
            const response = await axios.post(route("egresos.store", data), {
                headers: { Accept: "application/json" },
            });
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deleteEgreso = async (id) => {
        try {
            const response = await axios.delete(route("egresos.destroy", id), {
                headers: { Accept: "application/json" },
            });
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const setEgreso = (item = null, concepto = false, categoria = false) => {
        if (item) {
            oEgreso.id = item.id;
            oEgreso.fecha = item.fecha;
            oEgreso.fecha_t = item.fecha_t;
            oEgreso.total_c = item.total_c;
            oEgreso.total_m = item.total_m;
            oEgreso.total_m_txt = item.total_m_txt;
            oEgreso.categoria_id = item.categoria_id;
            if (concepto) {
                oEgreso.concepto = item.concepto;
            }
            if (categoria) {
                oEgreso.categoria = item.categoria;
            }
            oEgreso.egreso_detalles = reactive([...item.egreso_detalles]);
            oEgreso.eliminados = reactive([]);
            oEgreso._method = "PUT";
            return oEgreso;
        }
        return false;
    };

    const limpiarEgreso = () => {
        oEgreso.id = 0;
        oEgreso.fecha = "";
        oEgreso.categoria_id = null;
        oEgreso.total_c = 0;
        oEgreso.total_m = 0;
        oEgreso.total_m_txt = "0.00";
        oEgreso.egreso_detalles = reactive([]);
        oEgreso.eliminados = reactive([]);
        oEgreso._method = "POST";
    };

    onMounted(() => {});

    return {
        oEgreso,
        getEgresos,
        getEgresosApi,
        saveEgreso,
        deleteEgreso,
        setEgreso,
        limpiarEgreso,
    };
};
