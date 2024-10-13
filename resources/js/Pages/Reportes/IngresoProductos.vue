<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Ingreso de Productos",
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
const { setLoading } = useApp();

const { getTipoProductos } = useTipoProductos();
const { getProductos } = useProductos();

const form = ref({
    tipo_producto_id: "todos",
    producto_id: "todos",
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

const formulario = ref(null);

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte";
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
        const url = route("reportes.r_ingreso_productos", form.value);
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
    <Head title="Reporte Ingreso de Productos"></Head>
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
