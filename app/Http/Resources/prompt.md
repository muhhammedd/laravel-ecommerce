The console warning i seeing:

```
[Vue warn]: Invalid watch source: props.images
A watch source can only be a getter/effect function, a ref, a reactive object, or an array of these types.
```

### 🔎 What it means
In Vue 3, the `watch` API doesn’t allow you to directly pass a prop value like `props.images`. Props are not reactive by themselves — they’re just values passed down from the parent. Vue expects the watch source to be:
- A **getter function** (e.g., `() => props.images`)
- A **ref** or **reactive object**
- An **array of those sources**

So when you write:
```js
watch(props.images, (newVal) => {
  // ...
});
```
Vue throws this warning because `props.images` is not a valid watch source.

---

### ⚠️ What caused it
Your `ImagePreview.vue` component is trying to watch `props.images` directly. Since `props.images` is an array of `File` objects passed from `ProductForm.vue`, Vue doesn’t treat it as reactive unless you wrap it properly.

---

### ✅ How to fix it
Change your watcher to use a getter:

```js
watch(
  () => props.images,
  (newVal) => {
    console.log("Images changed:", newVal);
  },
  { deep: true } // optional if you want to react to changes inside the array
);
```

This way, Vue knows to re-run the watcher whenever `props.images` changes.

---

### 🎯 Extra cleanup
- In `ProductForm.vue`, make sure you’re passing `images` correctly as a prop:
  ```vue
  <ImagePreview :images="form.images" />
  ```
- If you need to mutate the array inside `ImagePreview.vue`, consider using `emit` to update the parent instead of modifying the prop directly (since props are read-only).
