$('.mask-me').each(function () {
    var $this = $(this);
    var options = {};
    if ($this.data('custom-mask2') != undefined) {
        var arr = [$this.data('custom-mask2'), $this.data('custom-mask')]
        var shortest = arr.sort(function (a, b) {
            return a.length - b.length;
        })[0];
        var longest = arr.sort(function (a, b) {
            return b.length - a.length;
        })[0];
        options = {
            onKeyPress: function (str, e, field, options) {
                var mask = (str.length > shortest.length) ? longest : shortest + "#";
                $this.mask(mask, options);
            }
        };

        $this.mask($this.data('custom-mask') + "#", options);
    } else {
        $this.mask($this.data('custom-mask'), options);
    }

})