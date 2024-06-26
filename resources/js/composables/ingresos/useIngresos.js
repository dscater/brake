import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oIngreso = reactive({
    id: 0,
    fecha: "",
    categoria_id: null,
    ingreso_detalles: reactive([]),
    eliminados: reactive([]),
    total_c: 0,
    total_m: 0,
    total_m_txt: "0.00",
    _method: "POST",
});

export const useIngresos = () => {
    const { flash } = usePage().props;
    const getIngresos = async (data) => {
        try {
            const response = await axios.get(route("ingresos.listado"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.ingresos;
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

    const getIngresosApi = async (data) => {
        try {
            const response = await axios.get(route("ingresos.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.ingresos;
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
    const saveIngreso = async (data) => {
        try {
            const response = await axios.post(route("ingresos.store", data), {
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

    const deleteIngreso = async (id) => {
        try {
            const response = await axios.delete(route("ingresos.destroy", id), {
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

    const setIngreso = (item = null, concepto = false, categoria = false) => {
        if (item) {
            oIngreso.id = item.id;
            oIngreso.fecha = item.fecha;
            oIngreso.fecha_t = item.fecha_t;
            oIngreso.total_c = item.total_c;
            oIngreso.total_m = item.total_m;
            oIngreso.total_m_txt = item.total_m_txt;
            oIngreso.categoria_id = item.categoria_id;
            if (concepto) {
                oIngreso.concepto = item.concepto;
            }
            if (categoria) {
                oIngreso.categoria = item.categoria;
            }
            oIngreso.ingreso_detalles = reactive([...item.ingreso_detalles]);
            oIngreso.eliminados = reactive([]);
            oIngreso._method = "PUT";
            return oIngreso;
        }
        return false;
    };

    const limpiarIngreso = () => {
        oIngreso.id = 0;
        oIngreso.fecha = "";
        oIngreso.categoria_id = null;
        oIngreso.total_c = 0;
        oIngreso.total_m = 0;
        oIngreso.total_m_txt = "0.00";
        oIngreso.ingreso_detalles = reactive([]);
        oIngreso.eliminados = reactive([]);
        oIngreso._method = "POST";
    };

    onMounted(() => {});

    return {
        oIngreso,
        getIngresos,
        getIngresosApi,
        saveIngreso,
        deleteIngreso,
        setIngreso,
        limpiarIngreso,
    };
};
