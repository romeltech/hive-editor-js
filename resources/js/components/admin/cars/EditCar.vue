<template>
  <div>
    <car-form
      :objectdata="objectData"
      :pagetitle="'edit'"
      @saved="savedResponse"
    ></car-form>
  </div>
</template>

<script>
import CarForm from "./CarForm.vue";

export default {
  components: { CarForm },
  data() {
    return {
      objectData: {},
    };
  },
  methods: {
    getData() {
      axios
        .get("/d/car/get/" + this.$route.params.id)
        .then((response) => {
          this.objectData = Object.assign({}, response.data);
        })
        .catch((err) => {});
    },
    savedResponse() {
      this.getData();
    },
  },
  mounted() {
    this.getData();
  },
};
</script>

<style>
</style>
