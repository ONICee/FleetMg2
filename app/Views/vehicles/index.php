<h1 class="mb-4">Vehicles</h1>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>ID</th>
      <th>Brand</th>
      <th>Model</th>
      <th>Serial</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($vehicles as $v): ?>
      <tr>
        <td><?= $v['id'] ?></td>
        <td><?= htmlspecialchars($v['brand']) ?></td>
        <td><?= htmlspecialchars($v['model']) ?></td>
        <td><?= htmlspecialchars($v['serial_number']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>