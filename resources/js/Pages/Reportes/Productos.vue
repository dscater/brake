<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Lista de Productos",
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
import { useProductos } from "@/composables/productos/useProductos";
import { useCategorias } from "@/composables/categorias/useCategorias";
import { useTipoProductos } from "@/composables/tipo_productos/useTipoProductos";
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
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte";
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

const generarReporte = () => {
    generando.value = true;
    const url = route("reportes.r_productos", form.value);
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

onMounted(() => {
    cargarListas();
});
</script>
<template>
    <Head title="Reporte Lista de Productos"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row>
            <v-col cols="12" sm="12" md="12" xl="8" class="mx-auto">
                <v-card>
                    <v-card-item>
                        <v-container>
                            <form @submit.prevent="generarReporte">
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
                                        <v-btn
                                            color="yellow-accent-3"
                                            block
                                            @click="generarReporte"
                                            :disabled="generando"
                                            v-text="txtBtn"
                                        ></v-btn>
                                    </v-col>
                                </v-row>
                            </form>
                        </v-container>
                    </v-card-item>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
