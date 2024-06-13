<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Análisis de almacén",
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
import { useTipoIngresos } from "@/composables/tipo_ingresos/useTipoIngresos";
import { useTipoSalidas } from "@/composables/tipo_salidas/useTipoSalidas";

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
const { getTipoIngresos } = useTipoIngresos();
const { getTipoSalidas } = useTipoSalidas();
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
    tipo_ingreso_id: "todos",
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});

const form2 = ref({
    filtro: "todos",
    producto_id: "todos",
    categoria_id: "todos",
    tipo_producto_id: "todos",
    tipo_salida_id: "todos",
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});
const form3 = ref({
    filtro: "todos",
    producto_id: "todos",
    categoria_id: "todos",
    tipo_producto_id: "todos",
});

const generando = ref(false);
const txtBtn = computed(() => {
    return `Generar <i class="mdi mdi-chart-bar"></i>`;
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
    {
        value: "tipo_ingreso",
        label: "POR TIPO DE INGRESO",
    },
]);

const listFiltro2 = ref([
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
    {
        value: "tipo_salida",
        label: "POR TIPO DE SALIDA",
    },
]);

const listFiltro3 = ref([
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
const listTipoIngresos = ref([]);
const listTipoSalidas = ref([]);

const formulario = ref(null);
const formulario2 = ref(null);
const formulario3 = ref(null);
const generarReporte1 = async () => {
    const { valid } = await formulario.value.validate();
    if (valid) {
        generando.value = true;

        axios
            .get(route("reportes.rg_ingreso_productos"), { params: form.value })
            .then((response) => {
                // Create the chart
                Highcharts.chart("container", {
                    chart: {
                        type: "column",
                    },
                    title: {
                        align: "center",
                        text: "Ingreso de Productos",
                    },
                    subtitle: {
                        align: "left",
                        text: "",
                    },
                    accessibility: {
                        announceNewData: {
                            enabled: true,
                        },
                    },
                    xAxis: {
                        type: "category",
                    },
                    yAxis: {
                        title: {
                            text: "Total",
                        },
                    },
                    legend: {
                        enabled: false,
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: "{point.y:.2f}",
                            },
                        },
                    },

                    tooltip: {
                        headerFormat:
                            '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat:
                            '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> %<br/>',
                    },

                    series: [
                        {
                            name: "Total Ingresos",
                            colorByPoint: true,
                            data: response.data.data,
                        },
                    ],
                });
                generando.value = false;
            });
    }
};

const generarReporte2 = async () => {
    const { valid } = await formulario2.value.validate();
    if (valid) {
        generando.value = true;

        axios
            .get(route("reportes.rg_salida_productos"), { params: form2.value })
            .then((response) => {
                // Create the chart
                Highcharts.chart("container2", {
                    chart: {
                        type: "column",
                    },
                    title: {
                        align: "center",
                        text: "Salida de Productos",
                    },
                    subtitle: {
                        align: "left",
                        text: "",
                    },
                    accessibility: {
                        announceNewData: {
                            enabled: true,
                        },
                    },
                    xAxis: {
                        type: "category",
                    },
                    yAxis: {
                        title: {
                            text: "Total",
                        },
                    },
                    legend: {
                        enabled: false,
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: "{point.y:.2f}",
                            },
                        },
                    },

                    tooltip: {
                        headerFormat:
                            '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat:
                            '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> %<br/>',
                    },

                    series: [
                        {
                            name: "Total Salidas",
                            colorByPoint: true,
                            data: response.data.data,
                        },
                    ],
                });
                generando.value = false;
            });
    }
};

const generarReporte3 = async () => {
    const { valid } = await formulario3.value.validate();
    if (valid) {
        generando.value = true;

        axios
            .get(route("reportes.rg_inventario_productos"), {
                params: form3.value,
            })
            .then((response) => {
                // Create the chart
                Highcharts.chart("container3", {
                    chart: {
                        type: "column",
                    },
                    title: {
                        align: "center",
                        text: "Inventario de Productos",
                    },
                    subtitle: {
                        align: "left",
                        text: "",
                    },
                    accessibility: {
                        announceNewData: {
                            enabled: true,
                        },
                    },
                    xAxis: {
                        type: "category",
                    },
                    yAxis: {
                        title: {
                            text: "Total",
                        },
                    },
                    legend: {
                        enabled: false,
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: "{point.y:.2f}",
                            },
                        },
                    },

                    tooltip: {
                        headerFormat:
                            '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat:
                            '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> %<br/>',
                    },

                    series: [
                        {
                            name: "Total Ingresos",
                            colorByPoint: true,
                            data: response.data.data,
                        },
                    ],
                });
                generando.value = false;
            });
    }
};

const cargarListas = async () => {
    listProductos.value = await getProductos();
    listCategorias.value = await getCategorias();
    listTipoProductos.value = await getTipoProductos();
    listTipoIngresos.value = await getTipoIngresos();
    listTipoSalidas.value = await getTipoSalidas();

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
    listTipoIngresos.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
    listTipoSalidas.value.unshift({
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

    generarReporte1();
    generarReporte2();
    generarReporte3();
});
</script>
<template>
    <Head title="Análisis de almacén"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row>
            <v-col cols="12" md="12">
                <v-card>
                    <v-card-title class="pb-0 bg-blue-darken-3">
                        <h5 class="text-center mb-0">Ingreso de Productos</h5>
                    </v-card-title>
                    <v-card-item class="pt-0">
                        <v-container>
                            <v-form
                                @submit.prevent="generarReporte1"
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
                                    <v-col
                                        cols="12"
                                        v-if="form.filtro == 'tipo_ingreso'"
                                    >
                                        <v-select
                                            :hide-details="
                                                form.errors?.tipo_ingreso_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form.errors?.tipo_ingreso_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form.errors?.tipo_ingreso_id
                                                    ? form.errors
                                                          ?.tipo_ingreso_id
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listTipoIngresos"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar Tipo de Ingreso*"
                                            v-model="form.tipo_ingreso_id"
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
                                            color="yellow-accent-3 text-caption"
                                            block
                                            @click="generarReporte1"
                                            :disabled="generando"
                                            v-html="txtBtn"
                                        ></v-btn>
                                    </v-col>
                                    <v-col cols="12" class="contenedor_grafico">
                                        <div id="container"></div>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-container>
                    </v-card-item>
                </v-card>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                    <v-card-title class="pb-0 bg-blue-darken-3">
                        <h5 class="text-center mb-0">Salida de Productos</h5>
                    </v-card-title>
                    <v-card-item class="pt-0">
                        <v-container>
                            <v-form
                                @submit.prevent="generarReporte2"
                                ref="formulario2"
                            >
                                <v-row>
                                    <v-col cols="12">
                                        <v-select
                                            :hide-details="
                                                form2.errors?.filtro
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form2.errors?.filtro
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form2.errors?.filtro
                                                    ? form2.errors?.filtro
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listFiltro"
                                            item-value="value"
                                            item-title="label"
                                            label="Filtro*"
                                            v-model="form2.filtro"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="form2.filtro == 'producto'"
                                    >
                                        <v-select
                                            :hide-details="
                                                form2.errors?.producto_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form2.errors?.producto_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form2.errors?.producto_id
                                                    ? form2.errors?.producto_id
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listProductos"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar Producto*"
                                            v-model="form2.producto_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="form2.filtro == 'categoria'"
                                    >
                                        <v-select
                                            :hide-details="
                                                form2.errors?.categoria_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form2.errors?.categoria_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form2.errors?.categoria_id
                                                    ? form2.errors?.categoria_id
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listCategorias"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar Categoría*"
                                            v-model="form2.categoria_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="form2.filtro == 'tipo_producto'"
                                    >
                                        <v-select
                                            :hide-details="
                                                form2.errors?.tipo_producto_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form2.errors?.tipo_producto_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form2.errors?.tipo_producto_id
                                                    ? form2.errors
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
                                            v-model="form2.tipo_producto_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="form2.filtro == 'tipo_salida'"
                                    >
                                        <v-select
                                            :hide-details="
                                                form2.errors?.tipo_salida_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form2.errors?.tipo_salida_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form2.errors?.tipo_salida_id
                                                    ? form2.errors
                                                          ?.tipo_salida_id
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listTipoSalidas"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar Tipo de Salida*"
                                            v-model="form2.tipo_salida_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-row>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :hide-details="
                                                        form2.errors?.fecha_ini
                                                            ? false
                                                            : true
                                                    "
                                                    :error="
                                                        form2.errors?.fecha_ini
                                                            ? true
                                                            : false
                                                    "
                                                    :error-messages="
                                                        form2.errors?.fecha_ini
                                                            ? form2.errors
                                                                  ?.fecha_ini
                                                            : ''
                                                    "
                                                    density="compact"
                                                    variant="underlined"
                                                    color="primary"
                                                    type="date"
                                                    label="Fecha Inicio*"
                                                    required
                                                    v-model="form2.fecha_ini"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :hide-details="
                                                        form2.errors?.fecha_fin
                                                            ? false
                                                            : true
                                                    "
                                                    :error="
                                                        form2.errors?.fecha_fin
                                                            ? true
                                                            : false
                                                    "
                                                    :error-messages="
                                                        form2.errors?.fecha_fin
                                                            ? form2.errors
                                                                  ?.fecha_fin
                                                            : ''
                                                    "
                                                    density="compact"
                                                    variant="underlined"
                                                    color="primary"
                                                    type="date"
                                                    label="Fecha Fin*"
                                                    required
                                                    v-model="form2.fecha_fin"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn
                                            color="yellow-accent-3 text-caption"
                                            block
                                            @click="generarReporte2"
                                            :disabled="generando"
                                            v-html="txtBtn"
                                        ></v-btn>
                                    </v-col>
                                    <v-col cols="12" class="contenedor_grafico">
                                        <div id="container2"></div>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-container>
                    </v-card-item>
                </v-card>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                    <v-card-title class="pb-0 bg-blue-darken-3">
                        <h5 class="text-center mb-0">
                            Inventario de Productos
                        </h5>
                    </v-card-title>
                    <v-card-item class="pt-0">
                        <v-container>
                            <v-form
                                @submit.prevent="generarReporte3"
                                ref="formulario3"
                            >
                                <v-row>
                                    <v-col cols="12">
                                        <v-select
                                            :hide-details="
                                                form3.errors?.filtro
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form3.errors?.filtro
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form3.errors?.filtro
                                                    ? form3.errors?.filtro
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listFiltro"
                                            item-value="value"
                                            item-title="label"
                                            label="Filtro*"
                                            v-model="form3.filtro"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="form3.filtro == 'producto'"
                                    >
                                        <v-select
                                            :hide-details="
                                                form3.errors?.producto_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form3.errors?.producto_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form3.errors?.producto_id
                                                    ? form3.errors?.producto_id
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listProductos"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar Producto*"
                                            v-model="form3.producto_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="form3.filtro == 'categoria'"
                                    >
                                        <v-select
                                            :hide-details="
                                                form3.errors?.categoria_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form3.errors?.categoria_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form3.errors?.categoria_id
                                                    ? form3.errors?.categoria_id
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listCategorias"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar Categoría*"
                                            v-model="form3.categoria_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="form3.filtro == 'tipo_producto'"
                                    >
                                        <v-select
                                            :hide-details="
                                                form3.errors?.tipo_producto_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form3.errors?.tipo_producto_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form3.errors?.tipo_producto_id
                                                    ? form3.errors
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
                                            v-model="form3.tipo_producto_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn
                                            color="yellow-accent-3 text-caption"
                                            block
                                            @click="generarReporte3"
                                            :disabled="generando"
                                            v-html="txtBtn"
                                        ></v-btn>
                                    </v-col>
                                    <v-col cols="12" class="contenedor_grafico">
                                        <div id="container3"></div>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-container>
                    </v-card-item>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
#container,
#container2,
#container3 {
    min-width: 700px;
}

.contenedor_grafico{
    overflow: auto;
}
</style>
