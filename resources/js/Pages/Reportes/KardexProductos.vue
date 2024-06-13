<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Kardex de Productos",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>

<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import Highcharts from "highcharts";
import exporting from "highcharts/modules/exporting";
import { useProductos } from "@/composables/productos/useProductos";
import { useCategorias } from "@/composables/categorias/useCategorias";
import { useTipoProductos } from "@/composables/tipo_productos/useTipoProductos";

exporting(Highcharts);
Highcharts.setOptions({
    lang: {
        downloadPNG: "Descargar PNG",
        downloadJPEG: "Descargar JPEG",
        downloadPDF: "Descargar PDF",
        downloadSVG: "Descargar SVG",
        printChart: "Imprimir gráfico",
        contextButtonTitle: "Menú de exportación",
        viewFullscreen: "Pantalla completa",
        exitFullscreen: "Salir de pantalla completa",
    },
});

const { getCategorias } = useCategorias();
const { getTipoProductos } = useTipoProductos();
const { getProductos } = useProductos();
const { setLoading } = useApp();

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const form = ref({
    filtro: "todos",
    producto_id: "todos",
    categoria_id: "todos",
    tipo_producto_id: "todos",
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return `Generar Reporte Gráfico <i class="mdi mdi-chart-bar"></i>`;
});
const txtBtn2 = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return `Generar Reporte Pdf <i class="mdi mdi-file-pdf-box"></i>`;
});

const listFiltro = ref([
    {
        value: "todos",
        label: "TODOS",
    },
    {
        value: "producto",
        label: "POR PRODUCTO",
    },
    {
        value: "categoria",
        label: "POR CATEGORÍA",
    },
    {
        value: "tipo_producto",
        label: "POR TIPO DE PRODUCTO",
    },
]);
const listProductos = ref([]);
const listCategorias = ref([]);
const listTipoProductos = ref([]);

const formulario = ref(null);
const generarReportePdf = () => {
    generando.value = true;
    const url = route("reportes.r_kardex_productos", form.value);
    window.open(url, "_blank");
    setTimeout(() => {
        generando.value = false;
    }, 500);
};

const cargarListas = async () => {
    listProductos.value = await getProductos();
    listCategorias.value = await getCategorias();
    listTipoProductos.value = await getTipoProductos();

    listProductos.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
    listCategorias.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
    listTipoProductos.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
};

function obtenerFechaActual() {
    const fecha = new Date();
    const año = fecha.getFullYear();
    const mes = (fecha.getMonth() + 1).toString().padStart(2, "0");
    const dia = fecha.getDate().toString().padStart(2, "0");

    return `${año}-${mes}-${dia}`;
}

onMounted(() => {
    cargarListas();
});
</script>
<template>
    <Head title="Reporte Ingreso de Productos"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row>
            <v-col cols="12" sm="12" md="12" xl="8" class="mx-auto">
                <v-card>
                    <v-card-item>
                        <v-container>
                            <v-form
                                @submit.prevent="generarReportePdf"
                                ref="formulario"
                            >
                                <v-row>
                                    <v-col cols="12">
                                        <v-select
                                            :hide-details="
                                                form.errors?.filtro
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form.errors?.filtro
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form.errors?.filtro
                                                    ? form.errors?.filtro
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listFiltro"
                                            item-value="value"
                                            item-title="label"
                                            label="Filtro*"
                                            v-model="form.filtro"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="form.filtro == 'producto'"
                                    >
                                        <v-select
                                            :hide-details="
                                                form.errors?.producto_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form.errors?.producto_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form.errors?.producto_id
                                                    ? form.errors?.producto_id
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listProductos"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar Producto*"
                                            v-model="form.producto_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="form.filtro == 'categoria'"
                                    >
                                        <v-select
                                            :hide-details="
                                                form.errors?.categoria_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form.errors?.categoria_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form.errors?.categoria_id
                                                    ? form.errors?.categoria_id
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listCategorias"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar Categoría*"
                                            v-model="form.categoria_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="form.filtro == 'tipo_producto'"
                                    >
                                        <v-select
                                            :hide-details="
                                                form.errors?.tipo_producto_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form.errors?.tipo_producto_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form.errors?.tipo_producto_id
                                                    ? form.errors
                                                          ?.tipo_producto_id
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listTipoProductos"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar Tipo de Producto*"
                                            v-model="form.tipo_producto_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-row>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :hide-details="
                                                        form.errors?.fecha_ini
                                                            ? false
                                                            : true
                                                    "
                                                    :error="
                                                        form.errors?.fecha_ini
                                                            ? true
                                                            : false
                                                    "
                                                    :error-messages="
                                                        form.errors?.fecha_ini
                                                            ? form.errors
                                                                  ?.fecha_ini
                                                            : ''
                                                    "
                                                    density="compact"
                                                    variant="underlined"
                                                    color="primary"
                                                    type="date"
                                                    label="Fecha Inicio*"
                                                    required
                                                    v-model="form.fecha_ini"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :hide-details="
                                                        form.errors?.fecha_fin
                                                            ? false
                                                            : true
                                                    "
                                                    :error="
                                                        form.errors?.fecha_fin
                                                            ? true
                                                            : false
                                                    "
                                                    :error-messages="
                                                        form.errors?.fecha_fin
                                                            ? form.errors
                                                                  ?.fecha_fin
                                                            : ''
                                                    "
                                                    density="compact"
                                                    variant="underlined"
                                                    color="primary"
                                                    type="date"
                                                    label="Fecha Fin*"
                                                    required
                                                    v-model="form.fecha_fin"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn
                                            color="yellow-accent-3"
                                            block
                                            @click="generarReportePdf"
                                            :disabled="generando"
                                            v-html="txtBtn2"
                                        ></v-btn>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-container>
                    </v-card-item>
                </v-card>
            </v-col>
            <v-col cols="12">
                <div id="container"></div>
            </v-col>
        </v-row>
    </v-container>
</template>
