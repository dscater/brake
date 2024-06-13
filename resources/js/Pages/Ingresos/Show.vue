<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Ingresos",
        disabled: false,
        url: route("ingresos.index"),
        name_url: "ingresos.index",
    },
    {
        title: "Ver",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { Head } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { useMenu } from "@/composables/useMenu";
import { useIngresos } from "@/composables/ingresos/useIngresos";
import Formulario from "./parcials/Formulario.vue";
const { mobile, identificaDispositivo, cambiarUrl } = useMenu();

const { setLoading } = useApp();
const { oIngreso, setIngreso } = useIngresos();

const props = defineProps({
    ingreso: {
        type: Object,
    },
});
setIngreso(props.ingreso, true, true);

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Ingresos"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>

        <v-row class="mt-0">
            <v-col cols="12" sm="12" md="12">
                <v-card>
                    <v-card-text>
                        <h4 class="text-h5 font-weight-bold mb-3">
                            Información Ingreso
                        </h4>
                        <p class="mb-2">
                            <strong>Proveedor: </strong>
                            {{ oIngreso.proveedor.razon_social }}
                        </p>
                        <p class="mb-2">
                            <strong>Tipo de Ingreso: </strong>
                            {{ oIngreso.tipo_ingreso.nombre }}
                        </p>
                        <p class="mb-2">
                            <strong>Precio: </strong> {{ oIngreso.precio }}
                        </p>
                        <p class="mb-2">
                            <strong>Nro. de Factura: </strong>
                            {{ oIngreso.nro_factura }}
                        </p>
                        <p class="mb-2">
                            <strong>Descripción: </strong>
                            {{ oIngreso.descripcion }}
                        </p>
                        <p class="mb-2">
                            <strong>Fecha de Ingreso: </strong>
                            {{ oIngreso.fecha_ingreso_t }}
                        </p>
                        <v-btn
                            prepend-icon="mdi-arrow-left"
                            class="mr-2"
                            @click="cambiarUrl(route('ingresos.index'))"
                        >
                            Volver</v-btn
                        >
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
