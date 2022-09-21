<template>
  <div>
    <div v-if="contentArray">
      <div
        v-for="render in contentArray"
        :key="render.id"
        :class="`content ${render.id}`"
      >
        <component
          v-bind:is="printComponent(render.type)"
          :content="render.data"
        />
      </div>
    </div>
    <div v-else>no content</div>
  </div>
</template>

<script>
import RenderParagraph from "./templates/RenderParagraph";
import RenderImage from "./templates/RenderImage";
export default {
  components: {
    RenderParagraph,
    RenderImage,
  },
  props: {
    contentData: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      contentArray: this.contentData
        ? JSON.parse(this.contentData).blocks
        : null,
    };
  },
  watch: {
    contentData: {
      handler(val, oldVal) {
        if (val) {
          this.contentArray = JSON.parse(val).blocks;
        }
      },
      deep: true,
    },
  },
  methods: {
    printComponent(text) {
      return "Render" + text.charAt(0).toUpperCase() + text.slice(1);
    },
  },
  created() {
    console.log("blocks", this.contentArray);
  },
};
</script>
