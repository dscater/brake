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
import { useIngresos } from "@/composables/ingresos/useIngresos";
import Formulario from "./parcials/Formulario.vue";
const { mobile, identificaDispositivo } = useMenu();
const { setLoading } = useApp();
const { oIngreso, setIngreso } = useIngresos();

const props = defineProps({
    ingreso: {
        type: Object,
    },
});
setIngreso(props.ingreso);

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
                <Formulario></Formulario>
            </v-col>
        </v-row>
    </v-container>
</template>
