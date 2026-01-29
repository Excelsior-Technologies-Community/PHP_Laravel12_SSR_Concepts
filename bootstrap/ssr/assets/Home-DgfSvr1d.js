import { ssrRenderAttrs, ssrInterpolate } from "vue/server-renderer";
import { ref, onServerPrefetch, useSSRContext } from "vue";
import axios from "axios";
const _sfc_main = {
  __name: "Home",
  __ssrInlineRender: true,
  setup(__props) {
    const data = ref({});
    const fetchData = async () => {
      const res = await axios.get("http://127.0.0.1:8000/api/home");
      data.value = res.data;
    };
    onServerPrefetch(fetchData);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(_attrs)}><h1>${ssrInterpolate(data.value.title)}</h1><p>${ssrInterpolate(data.value.message)}</p></div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Home.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
