-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `abaptran_data_param`()
BEGIN

  DECLARE loop_tran_done INT DEFAULT FALSE;
  DECLARE v_tcode VARCHAR(20);
  DECLARE v_param VARCHAR(254);
  DECLARE c_tran  CURSOR FOR SELECT tcode FROM abapanalytics.abaptran;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET loop_tran_done = TRUE;

  -- Clear the existing param value
  update abapanalytics.abaptran set param = null;

  -- Load the new param
  OPEN c_tran;
  loop_tran:LOOP
    set loop_tran_done = false;
    fetch c_tran into v_tcode;
    IF loop_tran_done = TRUE THEN
        LEAVE loop_tran;
    END IF;

    set v_param = null;
    select PARAM into v_param from abap.tstcp where TCODE = v_tcode;
    if LENGTH(TRIM(v_param)) > 0 then
      update abapanalytics.abaptran set param = v_param where tcode = v_tcode;
	end if;
  end loop;
  CLOSE c_tran;

  call abapanalytics.debug_msg('tran_data() ended');

END