<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Salidas",
        disabled: false,
        url: route("salidas.index"),
        name_url: "salidas.index",
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
import { useSalidas } from "@/composables/salidas/useSalidas";
import Formulario from "./parcials/Formulario.vue";
const { mobile, identificaDispositivo, cambiarUrl } = useMenu();

const { setLoading } = useApp();
const { oSalida, setSalida } = useSalidas();

const props = defineProps({
    salida: {
        type: Object,
    },
});
setSalida(props.salida, true, true);

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Salidas"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>

        <v-row class="mt-0">
            <v-col cols="12" sm="12" md="12">
                <v-card>
                    <v-card-text>
                        <h4 class="text-h5 font-weight-bold mb-3">
                            Información Salida
                        </h4>
                        <p class="mb-2">
                            <strong>Tipo de Salida: </strong>
                            {{ oSalida.tipo_salida.nombre }}
                        </p>
                        <p class="mb-2">
                            <strong>Unidad Solicitante: </strong> {{ oSalida.unidad_solicitante }}
                        </p>
                        <p class="mb-2">
                            <strong>Descripción: </strong>
                            {{ oSalida.descripcion }}
                        </p>
                        <p class="mb-2">
                            <strong>Fecha de Salida: </strong>
                            {{ oSalida.fecha_salida_t }}
                        </p>
                        <v-btn
                            prepend-icon="mdi-arrow-left"
                            class="mr-2"
                            @click="cambiarUrl(route('salidas.index'))"
                        >
                            Volver</v-btn
                        >
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
