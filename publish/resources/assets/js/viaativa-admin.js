function importAll (r) {
    r.keys().forEach(r);
}

importAll(require.context("./viaativa-admin/", false, /\.js$/));

