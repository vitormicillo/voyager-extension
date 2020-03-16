<?php

/*
 *  Return JSON response with Success Code
 */
if (!function_exists('json_response_with_success')) {
    function json_response_with_success($status, $message, $data = null)
    {
        return response()->json([
            'data' => [
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ],
        ]);
    }
}

/*
 *  Return JSON response with Error Code
 */
if (!function_exists('json_response_with_error')) {
    function json_response_with_error($status, Exception $error)
    {
        $code = $status;

        $message = __('voyager::generic.internal_error');

        if ($error->getCode()) {
            $code = $error->getCode();
        }

        if ($error->getMessage()) {
            $message = $error->getMessage();
        }

        return response()->json([
            'data' => [
                'status' => $code,
                'message' => $message,
            ],
        ], $code);
    }
}

/*
 *  Returns required ASSET file (css, js)
 */
if (!function_exists('voyager_extension_asset')) {
    function voyager_extension_asset($path, $secure = null)
    {
        return route('voyager.voyager_extension_assets').'?path='.urlencode($path);
    }
}


// Makes Multi Level TREE (array) from FLAT array (adds Children elements)
// Makes Multi Level TREE (array) from FLAT array (adds Children elements)
if (!function_exists('flat_to_tree'))
{
    function flat_to_tree($flat_array)
    {
        $result = $flat_array;
        $result = build_tree($result);
        return $result;
    }
}

// For flatToTree($flat_array)
if (!function_exists('build_tree'))
{
    function build_tree(array $elements, $parentId = null, $sort = true)
    {
        $branch = array();

        foreach ($elements as $element)
        {
            if ($element['parent_id'] == $parentId) {
                $children = build_tree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        if($sort) {
            usort($branch, function ($item1, $item2) {
                return $item1['order'] > $item2['order'];
            });
        }

        return $branch;
    }
}

// Makes FLAT Sorted Array from Multi level Sorted array TREE (excludes children elements)
if (!function_exists('build_flat_from_tree')) {
    function build_flat_from_tree($tree)
    {
        $result = [];
        $level = 0;

        build_flat_children($tree, $result, $level);

        return $result;
    }
}

// buildFlatFromTree($tree)
if (!function_exists('build_flat_children')) {
    function build_flat_children($children, &$result, &$level)
    {
        foreach ($children as $child) {
            $elements = [];
            foreach ($child as $key => $field) {
                if($key !== 'children') {
                    $elements[$key] = $field;
                    $elements['level'] = $level;
                }
            }
            $result[] = $elements;
            if (isset($child['children'])) {
                $level++;
                build_flat_children($child['children'], $result, $level);
                $level--;
            }
        }
    }
}
