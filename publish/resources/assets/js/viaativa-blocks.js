
function importAll (r) {
    r.keys().forEach(r);
}

importAll(require.context("./viaativa-blocks/", false, /\.js$/));
