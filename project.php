<?php
        // Include config file
        require_once "config.php";
        require_once "head.php";
        if(!isAdmin())
        {
            header("Location: http://jc-concepts.local/error.php");
        }

		$primary_key_ctr = 1;
		/* build database query,
		everything between SELECT and FROM are your table columns,
		to the right of FROM is your table */
		$sql = "SELECT * FROM `material`"; 
		$q2 = "SELECT * FROM `style`";	
		$material_name = array("Oak", "Cedar", "Pine", "Red Oak", "Plywood");
        $material_description = "This is a dummy.";
		$product_style = array("Modern", "Baroque", "Art Deco");

		$conn = db();

		//$room_component_part_style = array('Shelf', 'Cabinet', 'Door', 'Bar Top', 'Counter Top', 'Sheet');
	
		for ($i = 0; $i < sizeof($material_name); $i++)
		{
			$mat  = "INSERT INTO material (material_name, material_description, material_cost_per_unit) VALUES ('" . $material_name[$i] . "','This is a dummy description', " . $i . ")";
 
			// TEST
			// echo "INSERT INTO material (material_id, material_name, material_cost_per_unit) VALUES (". $primary_key_ctr++ . ",'" . $material_type[$i] . "'," . $i . ")<br>"; 
			if ($conn->query($mat) === TRUE) {
				echo "New material created successfully <br>";
			} 
			else {
			echo "Error: " . $mat . "<br>" . $conn->error;
			}

		}
		for ($i = 0; $i < sizeof($product_style); $i++)
		{

			$p_style  = "INSERT INTO style (style_description, style_name, style_year) VALUES ('Dummy description','" . $product_style[$i] . "',2017-06-15)";
 
			// TEST
			// echo "INSERT INTO style (style_id, style_description, style_name, style_year) VALUES (". $primary_key_ctr++ . ",'Dummy description','" . $product_style[$i] . "',2017-06-15)<br>";

			if ($conn->query($p_style) === TRUE) {
				echo "New style created successfully <br>";
			} 
			else {
			echo "Error: " . $p_style . "<br>" . $conn->error;
			}

		}
                      //echo "INSERT INTO `room_component_part`(`room_component_part_id`, `room_    component_part_type`, `Physical_Properties_physical_properties_id`) VALUES (11,'Shelf',10)";
                       // echo "INSERT INTO `physical_properties`(`physical_properties_id`, `length    `, `width`, `height`, `part_count`, `geometry`) VALUES (10,5,5,5,4,'squared')";
                        //echo "INSERT INTO `pp_h_m`(`physical_properties_id`, `material_id`) VALUES (10,2)";

		

		/* query database */
		$result = $conn->query($sql);
		$r2 = $conn->query($q2);

		echo "Here are all the materials: <br>";
		/* more than zero results */
		if ($result->num_rows > 0) {
		    /* fetch results */
		    while($row = $result->fetch_assoc()) {
		        echo "id: " . $row["material_id"]. " Material_Name: ". $row["material_name"]." - Cost  ". $row["material_cost_per_unit"]."<br>";
		    }
		} else {
		    echo "0 results";
		}

		echo "Here are all the styles: <br>";
		/* more than zero results */
		if ($r2->num_rows > 0) {
		    /* fetch results */
		    while($row = $r2->fetch_assoc()) {
		        echo "id: " . $row["style_id"]. " Style Description: ". $row["style_description"]." - Style Name  ". $row["style_name"]. " - Style Year " . $row["style_year"] . "<br>";
		    }
		} else {
		    echo "0 results";
		}
		$conn->close();
		?>
	</body>
</html>
