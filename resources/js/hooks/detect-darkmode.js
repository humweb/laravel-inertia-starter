export default function() {
  return window.matchMedia &&
      window.matchMedia('(prefers-color-scheme: dark)').matches;
}