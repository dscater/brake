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
        title: "Editar",
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
const { mobile, identificaDispositivo } = useMenu();
const { setLoading } = useApp();
const { oSalida, setSalida } = useSalidas();

const props = defineProps({
    salida: {
        type: Object,
    },
});
setSalida(props.salida);

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
                <Formulario></Formulario>
            </v-col>
        </v-row>
    </v-container>
</template>
