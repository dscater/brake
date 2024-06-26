<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Egresos",
        disabled: false,
        url: route("egresos.index"),
        name_url: "egresos.index",
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
import { useEgresos } from "@/composables/egresos/useEgresos";
import Formulario from "./parcials/Formulario.vue";
const { mobile, identificaDispositivo, cambiarUrl } = useMenu();

const { setLoading } = useApp();
const { oEgreso, setEgreso } = useEgresos();

const props = defineProps({
    egreso: {
        type: Object,
    },
});
setEgreso(props.egreso, true, true);

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Egresos"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>

        <v-row class="mt-0">
            <v-col cols="12" sm="12" md="12">
                <v-card>
                    <v-card-text>
                        <h4 class="text-h5 font-weight-bold mb-3">
                            Información Egreso Económico
                        </h4>
                        <p class="mb-2">
                            <strong>Categoría: </strong>
                            {{ oEgreso.categoria?.nombre }}
                        </p>
                        <p class="mb-2">
                            <strong>Fecha: </strong>
                            {{ oEgreso.fecha_t }}
                        </p>

                        <v-table class="border">
                            <thead>
                                <tr>
                                    <th class="text-center">N°</th>
                                    <th class="">Concepto</th>
                                    <th class="">Descripción</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(
                                        item_detalle, index
                                    ) in oEgreso.egreso_detalles"
                                >
                                    <td>
                                        {{ index + 1 }}
                                    </td>
                                    <td>
                                        {{ item_detalle.concepto?.nombre }}
                                    </td>
                                    <td>
                                        {{ item_detalle.descripcion }}
                                    </td>
                                    <td class="text-center">
                                        {{ item_detalle.cantidad }}
                                    </td>
                                    <td class="text-center">
                                        {{ item_detalle.monto_txt }}
                                    </td>
                                </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th
                                        colspan="3"
                                        class="text-right font-weight-bold"
                                    >
                                        TOTAL
                                    </th>
                                    <th class="text-center font-weight-bold">
                                        {{ oEgreso.total_c }}
                                    </th>
                                    <th class="text-center font-weight-bold">
                                        {{ oEgreso.total_m_txt }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </v-table>

                        <v-btn
                            prepend-icon="mdi-arrow-left"
                            class="mr-2 mt-3"
                            @click="cambiarUrl(route('egresos.index'))"
                        >
                            Volver</v-btn
                        >
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
