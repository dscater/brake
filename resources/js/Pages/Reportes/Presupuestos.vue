<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Presupuestos",
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
const { setLoading } = useApp();

const { getCategorias } = useCategorias();

const form = ref({
    fecha_ini: "",
    fecha_fin: "",
});

const existe_validacion_fechas = ref(false);

const rules_fechas = ref([
    (value) => {
        if (!value) {
            existe_validacion_fechas.value = true;
            return "Debes seleccionar una fecha";
        }
        const fechaSeleccionada = new Date(value);
        const fechaActual = new Date();

        if (fechaSeleccionada > fechaActual) {
            existe_validacion_fechas.value = true;
            return "La fecha no puede ser mayor a la fecha actual";
        }

        existe_validacion_fechas.value = false;
        return true;
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
    return "Generar Reporte";
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
        const url = route("reportes.r_presupuestos", form.value);
        window.open(url, "_blank");
        setTimeout(() => {
            generando.value = false;
        }, 500);
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
    <Head title="Reporte Presupuestos"></Head>
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
                                                    label="Fecha Final"
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
                                            v-text="txtBtn"
                                        ></v-btn>
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
