import { createInertiaApp } from "@inertiajs/vue3";
import { renderToString } from "@vue/server-renderer";
async function resolvePageComponent(path, pages) {
  for (const p of Array.isArray(path) ? path : [path]) {
    const page = pages[p];
    if (typeof page === "undefined") {
      continue;
    }
    return typeof page === "function" ? page() : page;
  }
  throw new Error(`Page not found: ${path}`);
}
function render(page) {
  return createInertiaApp({
    page,
    render: renderToString,
    resolve: (name) => resolvePageComponent(
      `./Pages/${name}.vue`,
      /* @__PURE__ */ Object.assign({ "./Pages/Home.vue": () => import("./assets/Home-DgfSvr1d.js") })
    ),
    setup({ App, props, plugin }) {
      return App;
    }
  });
}
export {
  render as default
};
