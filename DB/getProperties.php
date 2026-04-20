<?php
header('Content-Type: application/json; charset=utf-8');

try {
    include 'connection.php';

    $page = max(1, intval($_GET['page'] ?? 1));
    $perPage = max(1, min(100, intval($_GET['perPage'] ?? 3)));
    $offset = ($page - 1) * $perPage;

    $response = [
        'success' => false,
        'page' => $page,
        'perPage' => $perPage,
        'total' => 0,
        'totalPages' => 0,
        'properties' => [],
        'source' => 'api' // 'api' or 'local'
    ];

    $remoteUrl = 'https://raw.githubusercontent.com/anshumansinha1/real-estate-mock-api/master/db.json';
    $json = @file_get_contents($remoteUrl);

    $useApi = false;
    $listings = [];

    if ($json) {
        $data = json_decode($json, true);
        if (is_array($data) && isset($data['real-estate-data']['listings'])) {
            $listings = $data['real-estate-data']['listings'];
            $useApi = true;
        }
    }

    if (!$useApi) {
        // Fallback to local database
        $response['source'] = 'local';
        $countSql = "SELECT COUNT(*) AS total FROM Property";
        $countResult = mysqli_query($dbconid, $countSql);
        if ($countResult && $countRow = mysqli_fetch_assoc($countResult)) {
            $response['total'] = intval($countRow['total']);
        }

        $response['totalPages'] = $response['total'] > 0 ? max(1, ceil($response['total'] / $perPage)) : 1;

        $propertySql = "SELECT p.property_ID,
            p.property_name,
            p.property_price,
            p.property_profile,
            p.property_location,
            p.property_area,
            p.no_of_bedroom,
            p.no_of_bathroom,
            p.property_status,
            pt.ptype,
            CASE
                WHEN EXISTS (SELECT 1 FROM Purchase_Property pp WHERE pp.property_ID = p.property_ID LIMIT 1)
                    THEN 'Reserved'
                ELSE p.property_status
            END AS effective_status
            FROM Property p
            LEFT JOIN Property_type pt ON pt.pt_ID = p.pt_ID
            ORDER BY p.property_ID DESC
            LIMIT ?, ?";

        $stmt = mysqli_prepare($dbconid, $propertySql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ii', $offset, $perPage);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $response['properties'][] = [
                        'property_ID' => intval($row['property_ID'] ?? 0),
                        'property_name' => $row['property_name'] ?? '',
                        'property_price' => floatval($row['property_price'] ?? 0),
                        'property_profile' => !empty($row['property_profile']) ? '../Admin/imgUpload/' . $row['property_profile'] : '',
                        'property_location' => $row['property_location'] ?? '',
                        'property_area' => floatval($row['property_area'] ?? 0),
                        'no_of_bedroom' => intval($row['no_of_bedroom'] ?? 0),
                        'no_of_bathroom' => intval($row['no_of_bathroom'] ?? 0),
                        'property_status' => $row['property_status'] ?? 'Available',
                        'effective_status' => $row['effective_status'] ?? $row['property_status'] ?? 'Available',
                        'ptype' => $row['ptype'] ?? '',
                        'description' => '',
                        'year_built' => '',
                        'listing_date' => ''
                    ];
                }
                $response['success'] = true;
            }
            mysqli_stmt_close($stmt);
        }
        echo json_encode($response);
        exit;
    }

    // API is available, proceed with API data
    // Insert new properties from API into DB if not exists
    foreach ($listings as $item) {
        $apiId = $item['property_id'] ?? 0;
        $name = $item['property_name'] ?? '';
        if (empty($name)) continue;

        // Prepare strings for database insertion
        $name = mysqli_real_escape_string($dbconid, $item['property_name'] ?? '');
        $location = mysqli_real_escape_string($dbconid, trim(($item['address'] ?? '') . ', ' . ($item['city'] ?? '')));
        $type = mysqli_real_escape_string($dbconid, $item['property_type'] ?? 'Property');
        $desc = mysqli_real_escape_string($dbconid, $item['description'] ?? '');

        // Check if property with this name exists
        $checkSql = "SELECT property_ID FROM Property WHERE property_name = '$name' LIMIT 1";
        $checkResult = mysqli_query($dbconid, $checkSql);
        if (!$checkResult || mysqli_num_rows($checkResult) > 0) continue; // Skip if exists

        // Insert new property
        $price = floatval($item['price'] ?? 0);
        $area = floatval($item['square_footage'] ?? 0);
        $year = intval($item['year_built'] ?? 0);
        $profile = ''; // No image in API, use empty

        // Assume pt_ID = 1 for default type, or find by type
        $ptId = 1; // Default
        $typeCheckSql = "SELECT pt_ID FROM Property_type WHERE ptype = '$type' LIMIT 1";
        $typeResult = mysqli_query($dbconid, $typeCheckSql);
        if ($typeResult && $row = mysqli_fetch_assoc($typeResult)) {
            $ptId = intval($row['pt_ID']);
        }

        $insertSql = "INSERT INTO Property (property_name, property_price, property_profile, property_location, property_area, property_status, pt_ID, property_description, no_of_bedroom, no_of_bathroom)
                      VALUES ('$name', $price, '$profile', '$location', $area, 'Available', $ptId, '$desc', 0, 0)";
        $insertResult = mysqli_query($dbconid, $insertSql);
        if (!$insertResult) {
            // Skip this property if insert fails (likely due to encoding issues)
            continue;
        }
    }

    usort($listings, function ($a, $b) {
        return ($b['property_id'] ?? 0) <=> ($a['property_id'] ?? 0);
    });

    $response['total'] = count($listings);
    $response['totalPages'] = $response['total'] > 0 ? max(1, ceil($response['total'] / $perPage)) : 1;

    $pageListings = array_slice($listings, $offset, $perPage);

    foreach ($pageListings as $item) {
        $apiId = $item['property_id'] ?? 0;
        $name = $item['property_name'] ?? '';

        // Get local property_ID
        $localIdSql = "SELECT property_ID FROM Property WHERE property_name = '" . mysqli_real_escape_string($dbconid, $name) . "' LIMIT 1";
        $localIdResult = mysqli_query($dbconid, $localIdSql);
        $localId = 0;
        if ($localIdResult && $row = mysqli_fetch_assoc($localIdResult)) {
            $localId = intval($row['property_ID']);
        }

        $response['properties'][] = [
            'property_ID' => $localId,
            'property_name' => $item['property_name'] ?? '',
            'property_price' => $item['price'] ?? 0,
            'property_location' => trim(($item['address'] ?? '') . ', ' . ($item['city'] ?? '')),
            'property_area' => $item['square_footage'] ?? 0,
            'property_type' => $item['property_type'] ?? '',
            'property_status' => 'Available',
            'effective_status' => 'Available',
            'property_profile' => 'https://picsum.photos/seed/property' . $apiId . '/500/350',
            'year_built' => $item['year_built'] ?? '',
            'listing_date' => $item['listing_date'] ?? '',
            'description' => $item['description'] ?? ''
        ];
    }

    $response['success'] = true;

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}

