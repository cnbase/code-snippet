### 使用方法

```html
<div id="copy">这里可以是按钮</div>
<script type="text/javascript" src="js/clipboard.min.js"></script>
<script>
    var clipboard = new Clipboard('#copy',{
        // 点击copy按钮，直接通过text直接返回复印的内容
        text: function() {
            return 'this is copy text.';
        }
    });

    clipboard.on('success', function(e) {
        // 复制成功
        console.log(e);
    });

    clipboard.on('error', function(e) {
        //复制失败
        alert('复制失败，请手动复制');
        console.log(e);
    });
</script>
```