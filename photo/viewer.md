### 使用方法

1. **将css、js引入**

    ```html
    <link href="./viewer/viewer.css" rel="stylesheet" type="text/css"/>
    <script src="./viewer/viewer.js" type="text/javascript"></script>
    ```

2. **图片列表采用如下格式**

    ```html
    <ul class="images">
      <li><img src="./abc.jpg" alt="Picture" width="100"></li>
      <li><img src="./abc.jpg" alt="Picture 2" width="100"></li>
      <li><img src="./abc.jpg" alt="Picture 3" width="100"></li>
    </ul>
    ```

3. **使用**

    ```javascript
    <script type="text/javascript">
        // View some images
        $('.images').viewer();
    </script>
    ```
