<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include __DIR__ . '/style-splits.css'?>
        <?php
        $selfColumnNum = 3;
        foreach ($this->params['clients'] as $client) {
            $selfColumnNum += 1;
            if ($client['user_id'] === $_SESSION['user']) {
                $flag = 1;
                break;
            }
        }
        if (!isset($flag)) {
            $selfColumnNum = -1;
        }
        ?>
        .content_table td:nth-child(<?php echo $selfColumnNum ?>),
        .content_table th:nth-child(<?php echo $selfColumnNum ?>) {
            background-color: rgba(114, 255, 107, 0.3);
        }
    </style>
    <title>Home</title>
</head>
<body>

<?php
echo $this->getHeader($_SESSION['username'] ?? 'null', 'splits') ?>

<div class="container_view">
    <h1><?php
        echo $this->params['split']['title'] ?? 'null'
        ?></h1>

    <p><?php
        $dateCreated = new DateTime($this->params['split']['created_at']);
        $dateCreated->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));
        $dateUpdated = new DateTime($this->params['split']['updated_at']);
        $dateUpdated->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));
        $count = count($this->params['clients']);

        echo ($this->params['split']['is_public'] ? '&#x1F513; public' : '&#x1F512; private') .
            ' | created ' . $dateCreated->format('Y-m-d') . ' &mdash; ' . $dateCreated->format('H:i') .
            ' | updated ' . $dateUpdated->format('Y-m-d') . ' &mdash; ' . $dateUpdated->format('H:i') .
            ' | &#x1F464; ' . $this->params['split']['displayed_name'] .
            ' | ' . $count . ' client' . ($count !== 1 ? 's' : '');
        ?></p>

    <div class="container_btn">
        <a href="/splits/edit?s=<?php
        echo $this->params['split']['id'] ?>">
            <button class="split_btn">&#9881; Edit split</button>
        </a>

        <a href="/splits/access?s=<?php
        echo $this->params['split']['id'] ?>">
            <button class="split_btn">&#x1F310; Edit access</button>
        </a>

        <a href="/splits/delete?s=<?php
        echo $this->params['split']['id'] ?>">
            <button class="split_btn delete_btn">&#xFF0D;Delete split</button>
        </a>
    </div>

    <table class="content_table">
        <thead>
        <tr>
            <th>Item name</th>
            <th>Price</th>
            <th>Final price</th>
            <?php
            foreach ($this->params['clients'] as $client) {
                echo '<th style="width: 2px">' . $client['displayed_name'] . '</th>';
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($this->params['items'] as $item) {
            echo '<tr>';

            $itemCnt = 0;
            foreach ($this->params['clients'] as $client) {
                if (in_array($item['id'], $client['item_ids'])) {
                    $itemCnt += 1;
                    break;
                }
            }

            echo '<td ' . ($itemCnt === 0 ? 'class="unchecked"' : '') . '>' . $item['name'] . '</td>';
            echo '<td ' . ($itemCnt === 0 ? 'class="unchecked"' : '') . '>' . $item['base_price'] . '</td>';
            echo '<td ' . ($itemCnt === 0 ? 'class="unchecked"' : '') . '>' . $item['modified_price'] . '</td>';

            foreach ($this->params['clients'] as $client) {
                echo '<td>' . (in_array($item['id'], $client['item_ids']) ? '&check;' : '') . '</td>';
            }

            echo '</tr>';
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="1">Total</th>
            <td>
                <?php
                $totalPrice = 0;
                foreach ($this->params['items'] as $item) {
                    $totalPrice += $item['base_price'];
                }
                echo $totalPrice;
                ?>
            </td>
            <td>
                <?php
                $totalPrice = 0;
                foreach ($this->params['items'] as $item) {
                    $totalPrice += $item['modified_price'];
                }
                echo $totalPrice;
                ?>
            </td>
            <?php
            $totalPrice = [];
            foreach ($this->params['clients'] as $client) {
                $totalPrice[$client['user_id']] = 0;
            }

            foreach ($this->params['items'] as $item) {
                $itemClients = [];
                foreach ($this->params['clients'] as $client) {
                    if (in_array($item['id'], $client['item_ids'])) {
                        $itemClients[] = $client['user_id'];
                    }
                }

                $clientsCount = count($itemClients);
                if ($clientsCount === 0) {
                    break;
                }

                foreach ($itemClients as $clientId) {
                    $totalPrice[$clientId] += ((float)$item['modified_price']) / $clientsCount;
                }
            }

            foreach ($totalPrice as $price) {
                echo '<td>' . $price . '</td>';
            }
            ?>
        </tr>
        </tfoot>
    </table>
</div>

</body>
</html>