<!-- フッター -->
</div>
</body>
</html>
<script>
// どのページか判定した変数を取得し、対応するidをもつclass属性を書き換える
const div = document.getElementById("<?=$page?>");
div.classList.add('active');
</script>