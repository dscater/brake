<script setup>
import App from "@/Layouts/App.vue";
defineOptions({
    layout: App,
});
import { onMounted } from "vue";
import { useApp } from "@/composables/useApp";
// componentes
import BreadBrums from "@/Components/BreadBrums.vue";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
import { usePage, Head } from "@inertiajs/vue3";

const props_page = defineProps({
    array_infos: {
        type: Array,
    },
});

const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
];
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
const { oConfiguracion } = useConfiguracion();

const { props } = usePage();
</script>
<template>
    <Head title="Inicio"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>

        <v-row>
            <v-col
                cols="12"
                sm="6"
                md="6"
                xl="4"
                v-for="info_box in props_page.array_infos"
            >
                <v-card>
                    <v-card-text class="pa-1">
                        <v-row>
                            <v-col cols="4" class="pa-3">
                                <v-img
                                    :src="info_box.icon"
                                    class="ma-auto ml-2"
                                ></v-img>
                            </v-col>
                            <v-col
                                cols="8"
                                class="d-flex flex-column justify-center align-center"
                                :class="info_box.color"
                            >
                                <h4
                                    class="text-h5 text-center font-weight-black mb-3"
                                >
                                    {{ info_box.label }}
                                </h4>
                                <h4
                                    class="text-h4 text-center font-weight-black"
                                >
                                    {{ info_box.cantidad }}
                                </h4>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <v-card>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <h4 class="text-center text-h3">
                                    {{ oConfiguracion.nombre_sistema }}
                                </h4>
                            </v-col>
                            <v-col cols="12">
                                <h4 class="text-center text-h4">
                                    Bienvenid@ {{ props.auth.user.full_name }}
                                </h4>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
