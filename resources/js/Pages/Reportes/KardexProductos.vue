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
import { useTipoProductos } from "@/composables/tipo_productos/useTipoProductos";
import { useProductos } from "@/composables/productos/useProductos";
import Highcharts from "highcharts";
import exporting from "highcharts/modules/exporting";

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

const { setLoading } = useApp();

const { getTipoProductos } = useTipoProductos();
const { getProductos } = useProductos();

const form = ref({
    tipo_producto_id: "todos",
    producto_id: "todos",
});

const existe_validacion_fechas = ref(false);

const rules_fechas = ref([
    (value) => {
        if (value) {
            existe_validacion_fechas.value = false;
            return true;
        }
        existe_validacion_fechas.value = true;
        return "Debes seleccionar una fecha";
    },
]);

const formulario = ref(null);

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return 'Generar Reporte <i class="mdi mdi-file-pdf-box"></i>';
});
const txtBtn2 = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return 'Generar Reporte <i class="mdi mdi-chart-bar"></i>';
});

const listTipoProductos = ref([]);
const listProductos = ref([]);

const getProductosByTipoProducto = async (tipo_producto_id) => {
    if (tipo_producto_id != "") {
        if (tipo_producto_id != "todos") {
            axios
                .get(route("productos.byTipoProducto"), {
                    params: {
                        tipo_producto_id: tipo_producto_id,
                    },
                })
                .then((response) => {
                    listProductos.value = response.data.productos;
                    listProductos.value.unshift({
                        id: "todos",
                        nombre: "TODOS",
                    });
                    form.value.producto_id = "todos";
                });
        } else {
            listProductos.value = await getProductos();
            listProductos.value.unshift({
                id: "todos",
                nombre: "TODOS",
            });
        }
    }
};

const cargarListas = async () => {
    listTipoProductos.value = await getTipoProductos();
    listTipoProductos.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
    listProductos.value = await getProductos();
    listProductos.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
};

const generarReporte = async () => {
    const { valid } = await formulario.value.validate();
    if (valid) {
        generando.value = true;
        const url = route("reportes.r_kardex_productos", form.value);
        window.open(url, "_blank");
        setTimeout(() => {
            generando.value = false;
        }, 500);
    }
};

const generarReporteGraf = async () => {
    const { valid } = await formulario.value.validate();
    if (valid) {
        generando.value = true;

        axios
            .get(route("reportes.rg_kardex_productos"), { params: form.value })
            .then((response) => {
                console.log(response.data.categories);
                console.log(response.data.series);
                // Create the chart
                Highcharts.chart("container", {
                    chart: {
                        type: "column",
                    },
                    title: {
                        align: "center",
                        text: "Stock Productos",
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
                        enabled: true,
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                formatter: function () {
                                    if (this.series.name === "Monto") {
                                        return Highcharts.numberFormat(
                                            this.y,
                                            2,
                                            ".",
                                            ","
                                        );
                                    } else {
                                        return this.y;
                                    }
                                },
                            },
                        },
                    },

                    series: [
                        {
                            name: "Total",
                            data: response.data.data1,
                            colorByPoint: true,
                        },
                    ],
                });
                // Create the chart
                Highcharts.chart("container2", {
                    chart: {
                        type: "column",
                    },
                    title: {
                        align: "center",
                        text: "Valor Productos",
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
                        enabled: true,
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                formatter: function () {
                                    if (this.series.name === "Total") {
                                        return Highcharts.numberFormat(
                                            this.y,
                                            2,
                                            ".",
                                            ","
                                        );
                                    } else {
                                        return this.y;
                                    }
                                },
                            },
                        },
                    },

                    series: [
                        {
                            name: "Total",
                            data: response.data.data2,
                            colorByPoint: true,
                        },
                    ],
                });
                generando.value = false;
            });
    }
};

onMounted(() => {
    cargarListas();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Reporte Kardex de Productos"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row>
            <v-col cols="12" sm="12" md="12" xl="8" class="mx-auto">
                <v-card>
                    <v-card-item>
                        <v-container>
                            <v-form
                                @submit.prevent="generarReporte"
                                ref="formulario"
                            >
                                <v-row>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-autocomplete
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
                                            density="compact"
                                            variant="underlined"
                                            color="primary"
                                            no-data-text="Sin registros"
                                            :items="listTipoProductos"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar tipo de producto*"
                                            v-model="form.tipo_producto_id"
                                            @update:modelValue="
                                                getProductosByTipoProducto
                                            "
                                            required
                                        ></v-autocomplete>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6">
                                        <v-autocomplete
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
                                            density="compact"
                                            variant="underlined"
                                            color="primary"
                                            no-data-text="Sin registros"
                                            :items="listProductos"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar tipo de producto*"
                                            v-model="form.producto_id"
                                            required
                                        ></v-autocomplete>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn
                                            class="bg-principal"
                                            block
                                            @click="generarReporte"
                                            :disabled="generando"
                                            v-html="txtBtn"
                                        ></v-btn>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn
                                            class="bg-grey-lighten-4"
                                            block
                                            @click="generarReporteGraf"
                                            :disabled="generando"
                                            v-html="txtBtn2"
                                        ></v-btn>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <div id="container"></div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div id="container2"></div>
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
