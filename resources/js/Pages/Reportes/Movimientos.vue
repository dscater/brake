<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Movimientos de Ingresos y Egresos Económicos",
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
import { useCategorias } from "@/composables/categorias/useCategorias";
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

const { getCategorias } = useCategorias();

const form = ref({
    fecha_ini: "",
    fecha_fin: "",
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

const rules_concepto = ref([
    (value) => {
        if (value) {
            return true;
        }
        return "Debes seleccionar un concepto";
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

const listCategorias = ref([]);
const listConceptos = ref([]);

const cargarListas = async () => {
    listCategorias.value = await getCategorias({ byTipo: "EGRESO" });
    listCategorias.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
    listConceptos.value.unshift({
        id: "todos",
        nombre: "TODOS",
    });
};

const getConceptos = (categoria_id) => {
    form.concepto_id = "todos";
    listConceptos.value = [
        {
            id: "todos",
            nombre: "TODOS",
        },
    ];
    if (categoria_id != "") {
        axios
            .get(route("conceptos.byCategoria"), {
                params: {
                    categoria_id: categoria_id,
                },
            })
            .then((response) => {
                listConceptos.value = listConceptos.value.concat(response.data);
            });
    }
};

const generarReporte = async () => {
    const { valid } = await formulario.value.validate();
    if (valid) {
        generando.value = true;
        const url = route("reportes.r_movimientos", form.value);
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
            .get(route("reportes.rg_movimientos"), { params: form.value })
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
                        text: "Movimentos de Ingresos y Egresos Económicos",
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
                        categories: response.data.categories,
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

                    tooltip: {
                        formatter: function () {
                            var header =
                                '<span style="font-size:11px">' +
                                this.key +
                                "</span><br>";
                            if (this.series.name === "Monto") {
                                return (
                                    header +
                                    '<span style="color:' +
                                    this.point.color +
                                    '">' +
                                    this.series.name +
                                    "</span>: <b>" +
                                    Highcharts.numberFormat(
                                        this.point.y,
                                        2,
                                        ".",
                                        ","
                                    ) +
                                    "</b><br/>"
                                );
                            } else {
                                return (
                                    header +
                                    '<span style="color:' +
                                    this.point.color +
                                    '">' +
                                    this.series.name +
                                    "</span>: <b>" +
                                    this.point.y +
                                    "</b><br/>"
                                );
                            }
                        },
                    },

                    series: response.data.series,
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
    <Head title="Reporte Movimientos de Ingresos y Egresos Económicos"></Head>
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
                                    <v-col cols="12">
                                        <v-row>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :hide-details="
                                                        !existe_validacion_fechas
                                                    "
                                                    type="date"
                                                    variant="outlined"
                                                    label="Fecha Inicio"
                                                    required
                                                    density="compact"
                                                    v-model="form.fecha_ini"
                                                    :rules="rules_fechas"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :hide-details="
                                                        !existe_validacion_fechas
                                                    "
                                                    type="date"
                                                    variant="outlined"
                                                    label="Fecha Inicio"
                                                    required
                                                    density="compact"
                                                    v-model="form.fecha_fin"
                                                    :rules="rules_fechas"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
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
                                </v-row>
                            </v-form>
                        </v-container>
                    </v-card-item>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
